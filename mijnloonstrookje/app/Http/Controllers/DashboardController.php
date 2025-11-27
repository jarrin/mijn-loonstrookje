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
        // Verify user is actually an employer
        if (auth()->user()->role !== 'employer') {
            abort(403, 'Unauthorized access');
        }
        
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
    
    public function employerEmployees()
    {
        // Verify user is actually an employer
        if (auth()->user()->role !== 'employer') {
            abort(403, 'Unauthorized access');
        }
        
        // Get all users with role 'employee'
        $employees = User::where('role', 'employee')->get();
        
        return view('employer.EmployerEmployeeList', compact('employees'));
    }

    public function employerDocuments()
    {
        // Verify user is actually an employer
        if (auth()->user()->role !== 'employer') {
            abort(403, 'Unauthorized access');
        }
        
        return view('employer.EmployerEmployeeDocuments');
    }

    public function employerEmployeeDocuments($employeeId)
    {
        // Verify user is actually an employer
        if (auth()->user()->role !== 'employer') {
            abort(403, 'Unauthorized access');
        }
        
        $employee = User::where('id', $employeeId)
                       ->where('role', 'employee')
                       ->firstOrFail();
        
        // For now, we'll use dummy data for documents
        // Later this will be replaced with actual document model
        $documents = collect([
            // (object)['name' => 'Loonstrook Januari 2024', 'type' => 'Loonstrook', 'date' => '2024-01-31'],
            // (object)['name' => 'Loonstrook Februari 2024', 'type' => 'Loonstrook', 'date' => '2024-02-29'],
        ]);
        
        // When you want to use real data instead, replace the above with:
        // $documents = $employee->documents;
        
        // To disable dummy data and show empty table, uncomment:
        // $documents = collect([]);
        
        return view('employer.EmployerEmployeeDocuments', compact('employee', 'documents'));
    }

    public function employerAdminOffices()
    {
        // Verify user is actually an employer
        if (auth()->user()->role !== 'employer') {
            abort(403, 'Unauthorized access');
        }
        
        return view('employer.EmployerAdminOfficeList');
    }
}