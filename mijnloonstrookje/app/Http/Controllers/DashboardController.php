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
        return view('EmployeeDashboard');
    }

    public function employer()
    {
        return view('EmployerDashboard');
    }

    public function administration()
    {
        return view('AdministrationOfficeDashboard');
    }

    public function superAdmin()
    {
        return view('SuperAdminDashboard');
    }
}