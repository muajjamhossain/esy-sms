<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleAccess
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        $role = strtolower((string) ($user->role ?: $user->usertype));
        $route = (string) optional($request->route())->getName();

        if ($this->isAdmin($role)) {
            return $next($request);
        }

        if ($this->requiresAdminAccess($route)) {
            abort(403, 'You are not authorized to access this area.');
        }

        if ($this->requiresManagementAccess($route) && ! in_array($role, ['employee', 'teacher', 'staff'], true)) {
            abort(403, 'You are not authorized to access this area.');
        }

        if ($this->requiresStaffOnlyAccess($route) && ! in_array($role, ['employee', 'teacher', 'staff'], true)) {
            abort(403, 'You are not authorized to access this area.');
        }

        return $next($request);
    }

    private function requiresManagementAccess($route)
    {
        return str_starts_with($route, 'student.')
            || str_starts_with($route, 'roll.')
            || str_starts_with($route, 'marks.')
            || str_starts_with($route, 'routine.')
            || in_array($route, ['exam.routine', 'exam.routine.store', 'exam.routine.edit', 'exam.routine.update', 'exam.routine.destroy', 'admit.card', 'admit.card.generate', 'admit.card.print', 'admit.card.bulk.print', 'admit.card.delete', 'seat.plan', 'seat.plan.generate', 'seat.plan.view', 'seat.plan.delete'], true);
    }

    private function requiresAdminAccess($route)
    {
        return str_starts_with($route, 'user.')
            || str_starts_with($route, 'users.')
            || str_starts_with($route, 'employee.')
            || str_starts_with($route, 'fee.')
            || str_starts_with($route, 'store.')
            || str_starts_with($route, 'update.')
            || str_starts_with($route, 'school.subject.')
            || str_starts_with($route, 'assign.subject.')
            || str_starts_with($route, 'designation.')
            || str_starts_with($route, 'account.')
            || str_starts_with($route, 'other.cost.')
            || str_starts_with($route, 'monthly.');
    }

    private function requiresStaffOnlyAccess($route)
    {
        return in_array($route, [
            'assignments.create', 'assignments.store', 'assignments.feedback',
            'notices.create', 'notices.store',
            'events.create', 'events.store',
            'library.create', 'library.books.store', 'library.loans.issue', 'library.loans.return',
            'online-class.create', 'online-class.store', 'online-class.edit', 'online-class.update',
            'online-class.destroy', 'online-class.start', 'online-class.end', 'recordings.destroy',
        ], true);
    }

    private function isAdmin($role)
    {
        return in_array($role, ['admin', 'administrator'], true);
    }
}
