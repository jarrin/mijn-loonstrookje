<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->role;

        // Check if user has one of the required roles
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // Redirect to appropriate dashboard based on user role
        return $this->redirectToRoleDashboard($userRole);
    }

    private function redirectToRoleDashboard($role)
    {
        return match ($role) {
            'super_admin' => redirect()->route('superadmin.dashboard'),
            'administration_office' => redirect()->route('administration.dashboard'),
            'employer' => redirect()->route('employer.dashboard'),
            'employee' => redirect()->route('employee.documents'),
            default => redirect()->route('home'),
        };
    }
}
