<?php

namespace App\Http\Controllers;

use App\Models\AssignStudent;
use App\Models\InstitutionEvent;
use App\Models\StudentClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstitutionEventController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = InstitutionEvent::with(['creator', 'studentClass'])
            ->upcoming()
            ->orderBy('starts_at');

        if ($this->isStudent($user) || $this->isParent($user)) {
            $classIds = AssignStudent::where('student_id', $user->id)->pluck('class_id');
            $query->where(function ($target) use ($classIds) {
                $target->whereIn('audience', ['everyone', 'student'])
                    ->orWhereIn('class_id', $classIds);
            });
        } elseif (! $this->isAdministrator($user)) {
            $query->whereIn('audience', ['everyone', 'teacher', 'staff']);
        }

        $events = $query->get();

        return view('events.index', compact('events'));
    }

    public function create()
    {
        abort_unless($this->canPublish(Auth::user()), 403);
        $classes = StudentClass::orderBy('name')->get();

        return view('events.create', compact('classes'));
    }

    public function store(Request $request)
    {
        abort_unless($this->canPublish(Auth::user()), 403);

        $data = $request->validate([
            'class_id' => ['nullable', 'integer', 'exists:student_classes,id'],
            'title' => ['required', 'string', 'max:180'],
            'type' => ['required', 'in:event,holiday,exam,meeting'],
            'description' => ['nullable', 'string', 'max:10000'],
            'location' => ['nullable', 'string', 'max:180'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'audience' => ['required', 'in:everyone,student,teacher,staff'],
        ]);
        $data['created_by'] = Auth::id();
        InstitutionEvent::create($data);

        return redirect()->route('events.index')->with('message', __('messages.event_created'));
    }

    public function show(InstitutionEvent $event)
    {
        abort_unless($this->canView($event), 403);
        $event->load(['creator', 'studentClass']);

        return view('events.show', compact('event'));
    }

    private function canView(InstitutionEvent $event)
    {
        $user = Auth::user();
        if ($event->created_by === $user->id || $this->isAdministrator($user)) {
            return true;
        }

        if ($event->class_id && ($this->isStudent($user) || $this->isParent($user))) {
            return AssignStudent::where('student_id', $user->id)->where('class_id', $event->class_id)->exists();
        }

        if ($this->isStudent($user) || $this->isParent($user)) {
            return in_array($event->audience, ['everyone', 'student'], true);
        }

        return $event->audience === 'everyone' || in_array($event->audience, ['teacher', 'staff'], true);
    }

    private function isStudent($user)
    {
        return strtolower((string) ($user->usertype ?? '')) === 'student' || strtolower((string) ($user->role ?? '')) === 'student';
    }

    private function isParent($user)
    {
        return strtolower((string) ($user->usertype ?? '')) === 'parent' || strtolower((string) ($user->role ?? '')) === 'parent';
    }

    private function isAdministrator($user)
    {
        return strtolower((string) ($user->usertype ?? '')) === 'admin' || strtolower((string) ($user->role ?? '')) === 'admin';
    }

    private function canPublish($user)
    {
        return $this->isAdministrator($user)
            || in_array(strtolower((string) ($user->usertype ?? '')), ['employee', 'teacher', 'staff'], true)
            || in_array(strtolower((string) ($user->role ?? '')), ['employee', 'teacher', 'staff'], true);
    }
}
