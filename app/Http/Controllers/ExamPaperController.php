<?php

namespace App\Http\Controllers;

use App\Models\AssignStudent;
use App\Models\AssignSubject;
use App\Models\ExamPaper;
use App\Models\ExamSubmission;
use App\Models\ExamType;
use App\Models\SchoolSubject;
use App\Models\StudentClass;
use App\Models\StudentYear;
use App\Jobs\GradeExamSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExamPaperController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = ExamPaper::with(['studentClass', 'subject', 'examType', 'submissions']);

        if ($this->isStudent($user)) {
            $enrolments = AssignStudent::where('student_id', $user->id)->get(['year_id', 'class_id']);
            $query->where(function ($paperQuery) use ($enrolments) {
                foreach ($enrolments as $enrolment) {
                    $paperQuery->orWhere(function ($pairQuery) use ($enrolment) {
                        $pairQuery->where('year_id', $enrolment->year_id)->where('class_id', $enrolment->class_id);
                    });
                }
            });
        } elseif (! $this->isAdministrator($user)) {
            $query->where('created_by', $user->id);
        }

        return view('exam-papers.index', ['papers' => $query->latest()->get()]);
    }

    public function create()
    {
        abort_unless(! $this->isStudent(Auth::user()), 403);

        return view('exam-papers.create', [
            'classes' => StudentClass::orderBy('name')->get(),
            'years' => StudentYear::orderByDesc('id')->get(),
            'subjects' => SchoolSubject::orderBy('name')->get(),
            'examTypes' => ExamType::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        abort_unless(! $this->isStudent(Auth::user()), 403);

        $mcqQuestions = $request->input('mcq_questions');
        $rules = [
            'year_id' => ['required', 'exists:student_years,id'],
            'class_id' => ['required', 'exists:student_classes,id'],
            'subject_id' => ['required', 'exists:school_subjects,id'],
            'exam_type_id' => ['required', 'exists:exam_types,id'],
            'title' => ['required', 'string', 'max:180'],
            'max_marks' => ['required', 'numeric', 'min:0.01', 'max:9999.99'],
            'answer_key_file' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:20480'],
        ];

        if (empty($mcqQuestions)) {
            $rules['question_file'] = ['required', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:20480'];
        }

        $data = $request->validate($rules);
        abort_unless(AssignStudent::where('year_id', $data['year_id'])->where('class_id', $data['class_id'])->exists(), 422, 'No students are assigned to this year and class.');
        $data['created_by'] = Auth::id();

        if (! empty($mcqQuestions)) {
            $questions = $this->parseMcqQuestions($mcqQuestions);
            $data['questions'] = $questions;
            $data['question_file'] = null;
        } else {
            $data['question_file'] = $request->file('question_file')->store('exam-papers/questions', 'public');
        }

        if ($request->hasFile('answer_key_file')) {
            $data['answer_key_file'] = $request->file('answer_key_file')->store('exam-papers/keys', 'public');
        }

        ExamPaper::create($data);

        return redirect()->route('exam-papers.index')->with('message', __('messages.exam_paper_created'));
    }

    public function show(ExamPaper $examPaper)
    {
        $this->authorizePaper($examPaper);
        $examPaper->load(['studentClass', 'year', 'subject', 'examType', 'submissions.student']);
        $students = AssignStudent::with('student')
            ->where('year_id', $examPaper->year_id)
            ->where('class_id', $examPaper->class_id)
            ->orderBy('id')->get();
        $submission = $examPaper->submissions->firstWhere('student_id', Auth::id());

        return view('exam-papers.show', compact('examPaper', 'submission', 'students'));
    }

    public function submit(Request $request, ExamPaper $examPaper)
    {
        abort_unless($this->isStudent(Auth::user()), 403);
        $this->authorizePaper($examPaper);

        if ($examPaper->isMcq()) {
            $answers = $request->validate([
                'answers' => ['required', 'array'],
                'answers.*' => ['required', 'integer', 'between:0,3'],
            ]);

            $submission = ExamSubmission::updateOrCreate(
                ['exam_paper_id' => $examPaper->id, 'student_id' => Auth::id()],
                ['answers' => $answers['answers'], 'answer_file' => $request->input('answer_file', ''), 'final_marks' => null, 'reviewed_by' => null, 'reviewed_at' => null]
            );

            $finalMarks = $this->calculateMcqMarks($examPaper, $answers['answers']);
            $submission->update(['final_marks' => $finalMarks]);

            return redirect()->route('exam-papers.show', $examPaper)->with('message', __('messages.exam_submission_saved'));
        }

        $data = $request->validate([
            'answer_file' => ['required', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:20480'],
        ]);

        $submission = ExamSubmission::updateOrCreate(
            ['exam_paper_id' => $examPaper->id, 'student_id' => Auth::id()],
            ['answer_file' => $data['answer_file']->store('exam-papers/answers', 'public'), 'final_marks' => null, 'reviewed_by' => null, 'reviewed_at' => null]
        );
        $submission->update(['ai_marks' => null, 'ai_feedback' => 'Queued for automatic grading.']);
        GradeExamSubmission::dispatch($submission->id);

        return redirect()->route('exam-papers.show', $examPaper)->with('message', __('messages.exam_grading_queued'));
    }

    public function review(Request $request, ExamPaper $examPaper, ExamSubmission $submission)
    {
        abort_unless(! $this->isStudent(Auth::user()), 403);
        abort_unless($submission->exam_paper_id === $examPaper->id, 404);

        if ($examPaper->isMcq()) {
            $examPaper->update(['is_published' => true, 'published_at' => now()]);
            $submission->update(['reviewed_by' => Auth::id(), 'reviewed_at' => now()]);

            return redirect()->route('exam-papers.show', $examPaper)->with('message', __('messages.results_published'));
        }

        $data = $request->validate([
            'final_marks' => ['required', 'numeric', 'min:0', 'max:' . $examPaper->max_marks],
            'ai_feedback' => ['nullable', 'string', 'max:5000'],
        ]);
        $submission->update(array_merge($data, ['reviewed_by' => Auth::id(), 'reviewed_at' => now()]));
        $assignedSubject = AssignSubject::where('class_id', $examPaper->class_id)
            ->where('subject_id', $examPaper->subject_id)->first();
        if ($assignedSubject) {
            \App\Models\StudentMarks::updateOrCreate(
                [
                    'student_id' => $submission->student_id,
                    'class_id' => $examPaper->class_id,
                    'assign_subject_id' => $assignedSubject->id,
                    'exam_type_id' => $examPaper->exam_type_id,
                    'year_id' => $examPaper->year_id,
                ],
                [
                    'id_no' => $submission->student->id_no,
                    'marks' => $data['final_marks'],
                ]
            );
        }

        return redirect()->route('exam-papers.show', $examPaper)->with('message', __('messages.exam_review_saved'));
    }


    public function regrade(ExamPaper $examPaper, ExamSubmission $submission)
    {
        abort_unless(! $this->isStudent(Auth::user()), 403);
        abort_unless($submission->exam_paper_id === $examPaper->id, 404);

        $submission->update(['ai_marks' => null, 'ai_feedback' => 'Queued for automatic grading.']);
        GradeExamSubmission::dispatch($submission->id);

        return redirect()->route('exam-papers.show', $examPaper)
            ->with('message', __('messages.exam_grading_queued'));
    }

    public function uploadForStudent(Request $request, ExamPaper $examPaper)
    {
        abort_unless(! $this->isStudent(Auth::user()), 403);
        $data = $request->validate([
            'student_id' => ['required', 'exists:users,id'],
            'answer_file' => ['required', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:20480'],
        ]);
        abort_unless(AssignStudent::where('student_id', $data['student_id'])
            ->where('year_id', $examPaper->year_id)
            ->where('class_id', $examPaper->class_id)->exists(), 422, 'Student is not assigned to this exam class and year.');

        $submission = ExamSubmission::updateOrCreate(
            ['exam_paper_id' => $examPaper->id, 'student_id' => $data['student_id']],
            ['answer_file' => $data['answer_file']->store('exam-papers/answers', 'public'), 'final_marks' => null, 'reviewed_by' => null, 'reviewed_at' => null]
        );
        $submission->update(['ai_marks' => null, 'ai_feedback' => 'Queued for automatic grading.']);
        GradeExamSubmission::dispatch($submission->id);

        return redirect()->route('exam-papers.show', $examPaper)->with('message', __('messages.exam_grading_queued'));
    }

    public function publishResults(ExamPaper $examPaper)
    {
        abort_unless(! $this->isStudent(Auth::user()), 403);
        $this->authorizePaper($examPaper);

        $examPaper->update(['is_published' => true, 'published_at' => now()]);

        return redirect()->route('exam-papers.show', $examPaper)->with('message', __('messages.results_published'));
    }

    private function parseMcqQuestions($mcqQuestions)
    {
        $decoded = json_decode($mcqQuestions, true);
        if (! is_array($decoded)) {
            abort(422, 'Invalid MCQ data.');
        }

        $questions = [];
        foreach ($decoded as $question) {
            $questionText = trim((string) ($question['question'] ?? $question['question_text'] ?? ''));
            $language = in_array(($question['language'] ?? 'en'), ['bn', 'en'], true) ? ($question['language'] ?? 'en') : 'en';
            $options = [];
            foreach (range(0, 3) as $index) {
                $option = trim((string) ($question['options'][$index] ?? ''));
                if ($option === '') {
                    abort(422, 'Each MCQ question must include four options.');
                }
                $options[] = $option;
            }

            $correctOption = (int) ($question['correct_option'] ?? 0);
            if ($correctOption < 0 || $correctOption > 3) {
                abort(422, 'Correct option must be between A and D.');
            }

            if ($questionText === '') {
                abort(422, 'Each question requires text.');
            }

            $questions[] = [
                'question' => $questionText,
                'language' => $language,
                'options' => $options,
                'correct_option' => $correctOption,
            ];
        }

        if (empty($questions)) {
            abort(422, 'At least one MCQ question is required.');
        }

        return $questions;
    }

    private function calculateMcqMarks(ExamPaper $examPaper, array $answers): float
    {
        $totalQuestions = count($examPaper->questions ?? []);
        if ($totalQuestions === 0) {
            return 0.0;
        }

        $correctAnswers = 0;
        foreach ($examPaper->questions as $index => $question) {
            $selected = (int) ($answers[$index] ?? -1);
            if ($selected === (int) ($question['correct_option'] ?? -1)) {
                $correctAnswers++;
            }
        }

        return round(($correctAnswers / $totalQuestions) * (float) $examPaper->max_marks, 2);
    }

    private function authorizePaper(ExamPaper $paper)
    {
        $user = Auth::user();
        if ($paper->created_by === $user->id || $this->isAdministrator($user)) {
            return;
        }

        abort_unless(
            $this->isStudent($user)
                && AssignStudent::where('student_id', $user->id)->where('class_id', $paper->class_id)->where('year_id', $paper->year_id)->exists(),
            403
        );
    }

    private function isStudent($user)
    {
        return strtolower((string) ($user->role ?: $user->usertype)) === 'student';
    }

    private function isAdministrator($user)
    {
        return in_array(strtolower((string) ($user->role ?: $user->usertype)), ['admin', 'administrator'], true);
    }
}
