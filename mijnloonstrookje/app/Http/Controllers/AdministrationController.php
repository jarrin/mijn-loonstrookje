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
     * Display employees for specific company.
     */
    public function companyEmployees($companyId)
    {
        // Verify admin office has access to this company
        $company = auth()->user()->companies()
            ->wherePivot('status', 'active')
            ->where('companies.id', $companyId)
            ->firstOrFail();
        
        $employees = \App\Models\User::where('company_id', $companyId)
            ->where('role', 'employee')
            ->orderBy('name')
            ->get();
        
        return view('admin.AdminOfficeCompanyEmployees', compact('company', 'employees'));
    }
}
