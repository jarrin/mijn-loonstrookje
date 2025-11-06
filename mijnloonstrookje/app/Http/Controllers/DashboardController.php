<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        return view('superadmin.SuperAdminDashboard');
    }
}