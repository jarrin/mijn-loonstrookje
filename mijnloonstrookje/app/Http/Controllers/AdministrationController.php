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
        // Get companies where the authenticated admin office user has active access
        $companies = auth()->user()->companies()
            ->wherePivot('status', 'active')
            ->with('subscription')
            ->get();

        return view('admin.AdminOfficeDashboard', compact('companies'));
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
