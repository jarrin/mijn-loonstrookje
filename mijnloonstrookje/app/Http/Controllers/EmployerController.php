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
        
        // Get real documents from database
        $documents = $employee->documents()
                             ->where('is_deleted', false)
                             ->orderBy('year', 'desc')
                             ->orderBy('month', 'desc')
                             ->orderBy('week', 'desc')
                             ->get();
        
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
        
        // Get all documents for all employees in the company
        $documents = \App\Models\Document::where('company_id', auth()->user()->company_id)
                                        ->where('is_deleted', false)
                                        ->with(['employee', 'uploader'])
                                        ->orderBy('year', 'desc')
                                        ->orderBy('month', 'desc')
                                        ->orderBy('week', 'desc')
                                        ->get();
        
        $employee = null; // No specific employee selected
        
        return view('employer.EmployerEmployeeDocuments', compact('documents', 'employee'));
    }
}
