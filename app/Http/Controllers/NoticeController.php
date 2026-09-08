<?php

namespace App\Http\Controllers;

use App\Models\AssignStudent;
use App\Models\Notice;
use App\Models\StudentClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoticeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Notice::with(['creator', 'studentClass'])->visible()->latest('published_at');

        if ($this->isStudent($user) || $this->isParent($user)) {
            $classIds = AssignStudent::where('student_id', $user->id)->pluck('class_id');
            $query->where(function ($target) use ($classIds) {
                $target->where('audience', 'everyone')
                    ->orWhere('audience', 'student')
                    ->orWhereIn('class_id', $classIds);
            });
        } elseif (! $this->isAdministrator($user)) {
            $query->whereIn('audience', ['everyone', 'teacher', 'staff']);
        }

        $notices = $query->get();

        return view('notices.index', compact('notices'));
    }

    public function create()
    {
        abort_unless($this->canPublish(Auth::user()), 403);
        $classes = StudentClass::orderBy('name')->get();

        return view('notices.create', compact('classes'));
    }

    public function store(Request $request)
    {
        abort_unless($this->canPublish(Auth::user()), 403);

        $data = $request->validate([
            'class_id' => ['nullable', 'integer', 'exists:student_classes,id'],
            'title' => ['required', 'string', 'max:180'],
            'body' => ['required', 'string', 'max:10000'],
            'audience' => ['required', 'in:everyone,student,teacher,staff'],
            'published_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after_or_equal:published_at'],
        ]);
        $data['created_by'] = Auth::id();
        Notice::create($data);

        return redirect()->route('notices.index')->with('message', __('messages.notice_created'));
    }

    public function show(Notice $notice)
    {
        abort_unless($this->canView($notice), 403);

        $notice->load(['creator', 'studentClass']);

        return view('notices.show', compact('notice'));
    }

    private function canView(Notice $notice)
    {
        $user = Auth::user();
        if ($notice->created_by === $user->id || $this->isAdministrator($user)) {
            return true;
        }

        if (! $notice->published_at || $notice->published_at->isFuture() || ($notice->expires_at && $notice->expires_at->isPast())) {
            return false;
        }

        if ($notice->class_id && ($this->isStudent($user) || $this->isParent($user))) {
            return AssignStudent::where('student_id', $user->id)->where('class_id', $notice->class_id)->exists();
        }

        if ($this->isStudent($user) || $this->isParent($user)) {
            return in_array($notice->audience, ['everyone', 'student'], true);
        }

        return $notice->audience === 'everyone' || in_array($notice->audience, ['teacher', 'staff'], true);
    }

    private function isStudent($user)
    {
        return strtolower((string) ($user->usertype ?? '')) === 'student' || strtolower((string) ($user->role ?? '')) === 'student';
    }

    private function isAdministrator($user)
    {
        return strtolower((string) ($user->usertype ?? '')) === 'admin' || strtolower((string) ($user->role ?? '')) === 'admin';
    }

    private function isParent($user)
    {
        return strtolower((string) ($user->usertype ?? '')) === 'parent' || strtolower((string) ($user->role ?? '')) === 'parent';
    }

    private function canPublish($user)
    {
        return $this->isAdministrator($user)
            || in_array(strtolower((string) ($user->usertype ?? '')), ['employee', 'teacher', 'staff'], true)
            || in_array(strtolower((string) ($user->role ?? '')), ['employee', 'teacher', 'staff'], true);
    }
}
