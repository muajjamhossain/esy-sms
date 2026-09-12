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
use App\Services\ExamGradingService;
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

        $data = $request->validate([
            'year_id' => ['required', 'exists:student_years,id'],
            'class_id' => ['required', 'exists:student_classes,id'],
            'subject_id' => ['required', 'exists:school_subjects,id'],
            'exam_type_id' => ['required', 'exists:exam_types,id'],
            'title' => ['required', 'string', 'max:180'],
            'max_marks' => ['required', 'numeric', 'min:0.01', 'max:9999.99'],
            'question_file' => ['required', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:20480'],
            'answer_key_file' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:20480'],
        ]);
        abort_unless(AssignStudent::where('year_id', $data['year_id'])->where('class_id', $data['class_id'])->exists(), 422, 'No students are assigned to this year and class.');
        $data['created_by'] = Auth::id();
        $data['question_file'] = $request->file('question_file')->store('exam-papers/questions', 'public');
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

    public function submit(Request $request, ExamPaper $examPaper, ExamGradingService $grader)
    {
        abort_unless($this->isStudent(Auth::user()), 403);
        $this->authorizePaper($examPaper);
        $data = $request->validate([
            'answer_file' => ['required', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:20480'],
        ]);

        $submission = ExamSubmission::updateOrCreate(
            ['exam_paper_id' => $examPaper->id, 'student_id' => Auth::id()],
            ['answer_file' => $data['answer_file']->store('exam-papers/answers', 'public'), 'final_marks' => null, 'reviewed_by' => null, 'reviewed_at' => null]
        );
        $result = $grader->grade($examPaper, $submission);
        if ($result) {
            $submission->update(['ai_marks' => $result['marks'], 'ai_feedback' => $result['feedback']]);
        }

        return redirect()->route('exam-papers.show', $examPaper)->with('message', __('messages.exam_submission_saved'));
    }

    public function review(Request $request, ExamPaper $examPaper, ExamSubmission $submission)
    {
        abort_unless(! $this->isStudent(Auth::user()), 403);
        abort_unless($submission->exam_paper_id === $examPaper->id, 404);
        $data = $request->validate([
            'final_marks' => ['required', 'numeric', 'min:0', 'max:'.$examPaper->max_marks],
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
                ['marks' => $data['final_marks']]
            );
        }

        public function regrade(ExamPaper $examPaper, ExamSubmission $submission, ExamGradingService $grader)
        {
            abort_unless(! $this->isStudent(Auth::user()), 403);
            abort_unless($submission->exam_paper_id === $examPaper->id, 404);

            $result = $grader->grade($examPaper, $submission);
            if (! $result) {
                return redirect()->route('exam-papers.show', $examPaper)
                    ->with('message', __('messages.exam_grading_unavailable'));
            }

            $submission->update(['ai_marks' => $result['marks'], 'ai_feedback' => $result['feedback']]);

            return redirect()->route('exam-papers.show', $examPaper)
                ->with('message', __('messages.exam_regraded'));
        }

        return redirect()->route('exam-papers.show', $examPaper)->with('message', __('messages.exam_review_saved'));
    }

    public function uploadForStudent(Request $request, ExamPaper $examPaper, ExamGradingService $grader)
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
        $result = $grader->grade($examPaper, $submission);
        if ($result) {
            $submission->update(['ai_marks' => $result['marks'], 'ai_feedback' => $result['feedback']]);
        }

        return redirect()->route('exam-papers.show', $examPaper)->with('message', __('messages.exam_submission_saved'));
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
