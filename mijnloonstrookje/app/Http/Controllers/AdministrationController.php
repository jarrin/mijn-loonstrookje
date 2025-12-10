<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdministrationController extends Controller
{
    public function __construct()
    {
        // Authentication and role verification handled by route middleware
    }

    /**
     * Display the administration dashboard.
     */
    public function dashboard()
    {
        return view('admin.AdminOfficeDashboard');
    }

    /**
     * Display list of employees.
     */
    public function employees()
    {
        return view('admin.AdminOfficeEmployeeList');
    }

    /**
     * Display documents overview.
     */
    public function documents()
    {
        return view('admin.AdminOfficeDocuments');
    }
}
