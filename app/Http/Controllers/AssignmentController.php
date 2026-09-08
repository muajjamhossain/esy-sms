<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentSubmission;
use App\Models\SchoolSubject;
use App\Models\StudentClass;
use App\Models\AssignStudent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Assignment::with(['creator', 'studentClass', 'subject', 'submissions']);

        if ($this->isStudent($user)) {
            $classIds = AssignStudent::where('student_id', $user->id)->pluck('class_id');
            $query->where(function ($assignmentQuery) use ($classIds) {
                $assignmentQuery->whereNull('class_id')->orWhereIn('class_id', $classIds);
            });
        } elseif (! $this->isAdministrator($user)) {
            $query->where('created_by', $user->id);
        }

        $assignments = $query->latest()->get();

        return view('assignments.index', compact('assignments'));
    }

    public function create()
    {
        abort_unless(! $this->isStudent(Auth::user()), 403);

        $classes = StudentClass::orderBy('name')->get();
        $subjects = SchoolSubject::orderBy('name')->get();

        return view('assignments.create', compact('classes', 'subjects'));
    }

    public function store(Request $request)
    {
        abort_unless(! $this->isStudent(Auth::user()), 403);

        $data = $request->validate([
            'class_id' => ['nullable', 'integer', 'exists:student_classes,id'],
            'subject_id' => ['nullable', 'integer', 'exists:school_subjects,id'],
            'title' => ['required', 'string', 'max:180'],
            'instructions' => ['required', 'string', 'max:10000'],
            'due_date' => ['nullable', 'date'],
        ]);
        $data['created_by'] = Auth::id();
        Assignment::create($data);

        return redirect()->route('assignments.index')->with('message', __('messages.assignment_created'));
    }

    public function show(Assignment $assignment)
    {
        $this->authorizeAssignment($assignment);
        $assignment->load(['creator', 'studentClass', 'subject', 'submissions.student']);
        $submission = $assignment->submissions->firstWhere('student_id', Auth::id());

        return view('assignments.show', compact('assignment', 'submission'));
    }

    public function submit(Request $request, Assignment $assignment)
    {
        abort_unless($this->isStudent(Auth::user()), 403);
        $this->authorizeAssignment($assignment);

        $data = $request->validate(['answer' => ['required', 'string', 'max:20000']]);
        AssignmentSubmission::updateOrCreate(
            ['assignment_id' => $assignment->id, 'student_id' => Auth::id()],
            ['answer' => $data['answer'], 'submitted_at' => now()]
        );

        return redirect()->route('assignments.show', $assignment)->with('message', __('messages.assignment_submitted'));
    }

    public function feedback(Request $request, Assignment $assignment, AssignmentSubmission $submission)
    {
        abort_unless($assignment->created_by === Auth::id() || $this->isAdministrator(Auth::user()), 403);
        abort_unless($submission->assignment_id === $assignment->id, 404);

        $data = $request->validate([
            'marks' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'feedback' => ['nullable', 'string', 'max:5000'],
        ]);
        $submission->update($data);

        return redirect()->route('assignments.show', $assignment)->with('message', __('messages.feedback_saved'));
    }

    private function authorizeAssignment(Assignment $assignment)
    {
        $user = Auth::user();
        if ($assignment->created_by === $user->id || $this->isAdministrator($user)) {
            return;
        }

        abort_unless(
            $this->isStudent($user) && AssignStudent::where('student_id', $user->id)->where('class_id', $assignment->class_id)->exists(),
            403
        );
    }

    private function isStudent($user)
    {
        return strtolower((string) ($user->usertype ?? '')) === 'student' || strtolower((string) ($user->role ?? '')) === 'student';
    }

    private function isAdministrator($user)
    {
        return strtolower((string) ($user->usertype ?? '')) === 'admin' || strtolower((string) ($user->role ?? '')) === 'admin';
    }
}
