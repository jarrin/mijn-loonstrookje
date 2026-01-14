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
        // Get all employees from companies that this admin office has access to
        $companyIds = auth()->user()->companies()
            ->wherePivot('status', 'active')
            ->pluck('companies.id');
        
        $employees = \App\Models\User::where('role', 'employee')
            ->whereIn('company_id', $companyIds)
            ->with('company')
            ->orderBy('name')
            ->get();
        
        return view('admin.AdminOfficeEmployeeList', compact('employees'));
    }

    /**
     * Display documents overview.
     */
    public function documents()
    {
        // Get all documents from companies that this admin office has access to
        $companyIds = auth()->user()->companies()
            ->wherePivot('status', 'active')
            ->pluck('companies.id');
        
        $documents = \App\Models\Document::whereIn('company_id', $companyIds)
            ->where('is_deleted', false)
            ->with(['employee', 'company', 'uploader'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.AdminOfficeDocuments', compact('documents'));
    }

    /**
     * Display specific company details.
     */
    public function showCompany($companyId)
    {
        // Verify admin office has access to this company
        $company = auth()->user()->companies()
            ->wherePivot('status', 'active')
            ->where('companies.id', $companyId)
            ->with('subscription')
            ->firstOrFail();
        
        // Get employees count
        $employeesCount = \App\Models\User::where('company_id', $companyId)
            ->where('role', 'employee')
            ->count();
        
        // Get documents count
        $documentsCount = \App\Models\Document::where('company_id', $companyId)
            ->where('is_deleted', false)
            ->count();
        
        return view('admin.AdminOfficeCompanyDetails', compact('company', 'employeesCount', 'documentsCount'));
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
