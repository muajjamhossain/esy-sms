<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class GlobalSearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $term = trim((string) $request->query('q'));

        if ($term === '') {
            return response()->json(['results' => []]);
        }

        $users = User::query()
            ->where(function ($query) use ($term) {
                $query->where('name', 'like', '%' . $term . '%')
                    ->orWhere('email', 'like', '%' . $term . '%')
                    ->orWhere('id_no', 'like', '%' . $term . '%');
            })
            ->orderBy('name')
            ->limit(8)
            ->get(['id', 'name', 'email', 'usertype', 'role']);

        $results = collect([
            ['label' => __('messages.dashboard'), 'type' => 'Menu', 'url' => route('dashboard')],
            ['label' => __('messages.portal'), 'type' => 'Menu', 'url' => route('portal.index')],
            ['label' => __('messages.assignments'), 'type' => 'Menu', 'url' => route('assignments.index')],
            ['label' => __('messages.notice_board'), 'type' => 'Menu', 'url' => route('notices.index')],
            ['label' => __('messages.academic_calendar'), 'type' => 'Menu', 'url' => route('events.index')],
            ['label' => __('messages.library'), 'type' => 'Menu', 'url' => route('library.index')],
            ['label' => __('messages.student_registration'), 'type' => 'Menu', 'url' => route('student.registration.view')],
            ['label' => __('messages.employee_registration'), 'type' => 'Menu', 'url' => route('employee.registration.view')],
        ])->filter(function (array $menu) use ($term) {
            return stripos($menu['label'], $term) !== false;
        })->map(function (array $menu) {
            return [
                'type' => $menu['type'],
                'name' => $menu['label'],
                'meta' => __('messages.menu'),
                'url' => $menu['url'],
            ];
        });

        $userResults = $users->map(function (User $user) {
            $isStudent = strtolower((string) $user->usertype) === 'student'
                || strtolower((string) $user->role) === 'student';

            return [
                'type' => $isStudent ? 'Student' : ($user->usertype ?: 'User'),
                'name' => $user->name,
                'meta' => $user->email,
                'url' => $isStudent
                    ? route('student.registration.details', $user->id)
                    : route('employee.registration.details', $user->id),
            ];
        });

        return response()->json(['results' => $results->concat($userResults)->values()]);
    }
}
