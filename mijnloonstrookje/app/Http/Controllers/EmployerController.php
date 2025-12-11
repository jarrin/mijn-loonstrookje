<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class EmployerController extends Controller
{
    public function __construct()
    {
        // Authentication and role verification handled by route middleware
    }

    /**
     * Display the employer dashboard.
     */
    public function dashboard()
    {
        // Verify user is actually an employer
        if (auth()->user()->role !== 'employer') {
            abort(403, 'Unauthorized access');
        }
        
        return view('employer.EmployerDashboard');
    }

    /**
     * Display list of all employees.
     */
    public function employees()
    {
        // Verify user is actually an employer
        if (auth()->user()->role !== 'employer') {
            abort(403, 'Unauthorized access');
        }
        
        // Get only employees from the employer's company
        $employees = User::where('role', 'employee')
                        ->where('company_id', auth()->user()->company_id)
                        ->get();
        
        return view('employer.EmployerEmployeeList', compact('employees'));
    }

    /**
     * Display documents for a specific employee.
     */
    public function employeeDocuments($employeeId)
    {
        // Verify user is actually an employer
        if (auth()->user()->role !== 'employer') {
            abort(403, 'Unauthorized access');
        }
        
        // Get employee only if they belong to the employer's company
        $employee = User::where('id', $employeeId)
                       ->where('role', 'employee')
                       ->where('company_id', auth()->user()->company_id)
                       ->firstOrFail();
        
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

    /**
     * Display list of administration offices.
     */
    public function adminOffices()
    {
        // Verify user is actually an employer
        if (auth()->user()->role !== 'employer') {
            abort(403, 'Unauthorized access');
        }
        
        return view('employer.EmployerAdminOfficeList');
    }

    /**
     * Display documents overview.
     */
    public function documents()
    {
        // Verify user is actually an employer
        if (auth()->user()->role !== 'employer') {
            abort(403, 'Unauthorized access');
        }
        
        return view('employer.EmployerEmployeeDocuments');
    }
}
