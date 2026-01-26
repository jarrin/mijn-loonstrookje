<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;

class EmployeeController extends Controller
{
    public function __construct()
    {
        // Authentication and role verification handled by route middleware
    }

    /**
     * Display the employee dashboard.
     */
    public function dashboard()
    {
        $user = auth()->user();
        
        // Get documents for this employee only
        $documents = Document::where('employee_id', $user->id)
                            ->where('is_deleted', false)
                            ->with(['uploader', 'company'])
                            ->orderBy('year', 'desc')
                            ->orderBy('month', 'desc')
                            ->orderBy('week', 'desc')
                            ->get();
        
        return view('employee.EmployeeDashboard', compact('documents'));
    }

    /**
     * Display employee's documents.
     */
    public function documents()
    {
        $user = auth()->user();
        
        // Get documents for this employee only
        $documents = Document::where('employee_id', $user->id)
                            ->where('is_deleted', false)
                            ->with(['uploader', 'company'])
                            ->orderBy('year', 'desc')
                            ->orderBy('month', 'desc')
                            ->orderBy('week', 'desc')
                            ->get();
        
        return view('employee.EmployeeDashboard', compact('documents'));
    }
}
