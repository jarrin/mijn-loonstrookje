<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        return view('employee.EmployeeDashboard');
    }
}
