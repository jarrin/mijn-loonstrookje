<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogController extends Controller
{
    /**
     * Display audit logs for Super Admin
     * Shows all logs from all companies with filters
     */
    public function superAdminLogs(Request $request)
    {
        if (Auth::user()->role !== 'super_admin') {
            abort(403, 'Unauthorized access');
        }
        
        $query = AuditLog::with(['user', 'company'])
            ->orderBy('created_at', 'desc');
        
        // Apply filters if provided
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }
        
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $logs = $query->paginate(50);
        
        // Get all unique actions for filter dropdown
        $actions = AuditLog::select('action')->distinct()->pluck('action');
        
        // Get all companies for filter dropdown
        $companies = \App\Models\Company::orderBy('name')->get();
        
        return view('superadmin.SuperAdminLogs', compact('logs', 'actions', 'companies'));
    }
    
    /**
     * Display recent audit logs for Employer Dashboard
     * Shows logs from their company only (employees + admin offices)
     */
    public function employerLogs()
    {
        $user = Auth::user();
        
        if ($user->role !== 'employer') {
            abort(403, 'Unauthorized access');
        }
        
        // Get last 10 audit logs for this company
        // Include logs where company_id matches OR user is admin office with access to this company
        $companyId = $user->company_id;
        $logs = AuditLog::with(['user'])
            ->where(function($query) use ($companyId) {
                $query->where('company_id', $companyId)
                      ->orWhereHas('user', function($q) use ($companyId) {
                          $q->where('role', 'administration_office')
                            ->whereHas('companies', function($c) use ($companyId) {
                                $c->where('company_id', $companyId);
                            });
                      });
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return $logs;
    }
}
