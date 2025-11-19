<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    public function __construct()
    {
        // Authentication will be handled by route middleware
    }

    public function employee()
    {
        return view('employee.EmployeeDashboard');
    }

    public function employer()
    {
        return view('employer.EmployerDashboard');
    }

    public function administration()
    {
        return view('admin.AdminOfficeDashboard');
    }

    public function superAdmin()
    {
        // Load users together with their company to display on the super admin dashboard
        $users = User::with('company')->get();

        return view('superadmin.SuperAdminDashboard', compact('users'));
    }

    /**
     * Delete a user (soft delete).
     * Prevent the currently authenticated user from deleting themselves.
     */
    public function destroyUser(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('superadmin.dashboard')->with('error', 'Je kunt jezelf niet verwijderen.');
        }

        $user->delete();

        return redirect()->route('superadmin.dashboard')->with('success', 'Gebruiker verwijderd.');
    }
}