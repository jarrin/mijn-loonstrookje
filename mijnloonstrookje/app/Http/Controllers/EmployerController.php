<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
        
        $company = auth()->user()->company;
        
        // Count employees for this company
        $employeeCount = User::where('role', 'employee')
                            ->where('company_id', auth()->user()->company_id)
                            ->count();
        
        // Get max employees based on subscription plan
        $maxEmployees = 50; // Default
        if ($company && $company->subscription) {
            switch ($company->subscription->subscription_plan) {
                case 'basic':
                    $maxEmployees = 5;
                    break;
                case 'pro':
                    $maxEmployees = 25;
                    break;
                case 'premium':
                    $maxEmployees = 999; // "Onbeperkt"
                    break;
            }
        }
        
        // Get next unpaid invoice
        $nextInvoice = \App\Models\Invoice::where('company_id', auth()->user()->company_id)
                            ->whereIn('status', ['pending', 'overdue'])
                            ->orderBy('due_date', 'asc')
                            ->first();
        
        // Get recent audit logs for this company
        $recentLogs = \App\Models\AuditLog::with(['user'])
                            ->where('company_id', auth()->user()->company_id)
                            ->orderBy('created_at', 'desc')
                            ->limit(10)
                            ->get();
        
        return view('employer.EmployerDashboard', compact('company', 'employeeCount', 'maxEmployees', 'nextInvoice', 'recentLogs'));
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
        $user = auth()->user();
        
        // Verify user is employer or administration office
        if (!in_array($user->role, ['employer', 'administration_office'])) {
            abort(403, 'Unauthorized access');
        }
        
        // Get employee based on user role
        if ($user->role === 'administration_office') {
            // Admin office: check if employee belongs to accessible company
            $companyIds = $user->companies()
                ->wherePivot('status', 'active')
                ->pluck('companies.id');
            
            $employee = User::where('id', $employeeId)
                ->where('role', 'employee')
                ->whereIn('company_id', $companyIds)
                ->firstOrFail();
        } else {
            // Employer: check if employee belongs to their company
            $employee = User::where('id', $employeeId)
                ->where('role', 'employee')
                ->where('company_id', $user->company_id)
                ->firstOrFail();
        }
        
        // Get real documents from database
        $documents = $employee->documents()
                             ->where('is_deleted', false)
                             ->orderBy('year', 'desc')
                             ->orderBy('month', 'desc')
                             ->orderBy('week', 'desc')
                             ->get();
        
        // Pass company for branding context
        $company = $employee->company;
        
        // Determine back URL based on user role
        if ($user->role === 'administration_office') {
            // Admin office: back to company employees page
            $backUrl = route('administration.company.employees', $company->id);
        } else {
            // Employer: back to their employees page
            $backUrl = route('employer.employees');
        }
        
        return view('employer.EmployerEmployeeDocuments', compact('employee', 'documents', 'company', 'backUrl'));
    }

    /**
     * Display list of administration offices linked to this company.
     */
    public function adminOffices()
    {
        // Verify user is actually an employer
        if (auth()->user()->role !== 'employer') {
            abort(403, 'Unauthorized access');
        }
        
        // Get only administration offices linked to this company
        $company = auth()->user()->company;
        
        if (!$company) {
            return view('employer.EmployerAdminOfficeList', ['adminOffices' => collect(), 'pendingInvitations' => collect()]);
        }
        
        $adminOffices = $company->adminOffices()->get();
        
        // Get pending invitations for admin offices
        $pendingInvitations = \App\Models\Invitation::where('company_id', $company->id)
            ->where('role', 'administration_office')
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->get();
        
        return view('employer.EmployerAdminOfficeList', compact('adminOffices', 'pendingInvitations'));
    }

    /**
     * Send invitation to administration office.
     */
    public function inviteAdminOffice(Request $request)
    {
        // Verify user is actually an employer
        if (auth()->user()->role !== 'employer') {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ], [
            'email.required' => 'E-mailadres is verplicht.',
            'email.email' => 'Voer een geldig e-mailadres in.',
        ]);

        // Check if user already exists as admin office
        $existingUser = User::where('email', $request->email)
            ->where('role', 'administration_office')
            ->first();
        
        // If user exists, check if already linked to this company
        if ($existingUser) {
            $alreadyLinked = $existingUser->companies()
                ->where('company_id', auth()->user()->company_id)
                ->exists();
            
            if ($alreadyLinked) {
                return back()->with('error', 'Dit administratiekantoor heeft al toegang tot jouw bedrijf.');
            }
        }
        
        $invitationType = $existingUser ? 'company_access' : 'new_account';
        $isNewAccount = !$existingUser;

        // Check if there's already a pending invitation for this company
        $existingInvitation = \App\Models\Invitation::where('email', $request->email)
            ->where('company_id', auth()->user()->company_id)
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->first();

        if ($existingInvitation) {
            return back()->with('error', 'Er is al een actieve uitnodiging voor dit e-mailadres voor jouw bedrijf.');
        }

        // Create invitation
        $invitation = \App\Models\Invitation::create([
            'email' => $request->email,
            'token' => \App\Models\Invitation::generateToken(),
            'employer_id' => auth()->id(),
            'invited_by' => auth()->id(),
            'company_id' => auth()->user()->company_id,
            'role' => 'administration_office',
            'invitation_type' => $invitationType,
            'status' => 'pending',
            'expires_at' => \Carbon\Carbon::now()->addDays(7),
        ]);

        // Get employer and company details
        $employer = auth()->user();
        $companyName = $employer->company ? $employer->company->name : 'Uw Bedrijf';

        // Send appropriate email based on whether user exists
        try {
            \Illuminate\Support\Facades\Mail::to($request->email)
                ->send(new \App\Mail\AdminOfficeInvitation($invitation, $employer->name, $companyName, $isNewAccount));
            
            $message = $isNewAccount 
                ? 'Uitnodiging succesvol verzonden. Het administratiekantoor ontvangt een e-mail om een account aan te maken.'
                : 'Uitnodiging succesvol verzonden. Het administratiekantoor ontvangt een e-mail om toegang te accepteren.';
            
            return back()->with('success', $message);
        } catch (\Exception $e) {
            // Delete invitation if email fails
            $invitation->delete();
            
            return back()
                ->withInput()
                ->with('error', 'Er is een fout opgetreden bij het verzenden van de uitnodiging: ' . $e->getMessage());
        }
    }



    /**
     * Update administration office access status for this company.
     */
    public function updateAdminOffice(Request $request, User $adminOffice)
    {
        // Verify user is actually an employer
        if (auth()->user()->role !== 'employer') {
            abort(403, 'Unauthorized access');
        }

        // Verify target is an administration office
        if ($adminOffice->role !== 'administration_office') {
            abort(403, 'Invalid user type');
        }

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:active,inactive,pending'],
        ]);

        // Update the pivot table status
        $company = auth()->user()->company;
        $company->adminOffices()->updateExistingPivot($adminOffice->id, [
            'status' => $validated['status'],
            'updated_at' => now(),
        ]);

        return redirect()->route('employer.admin-offices')->with('success', 'Toegangsstatus bijgewerkt.');
    }

    /**
     * Remove administration office access from this company.
     */
    public function destroyAdminOffice(User $adminOffice)
    {
        // Verify user is actually an employer
        if (auth()->user()->role !== 'employer') {
            abort(403, 'Unauthorized access');
        }

        // Verify target is an administration office
        if ($adminOffice->role !== 'administration_office') {
            abort(403, 'Invalid user type');
        }

        // Remove the relationship between company and admin office
        $company = auth()->user()->company;
        $company->adminOffices()->detach($adminOffice->id);
        
        // Also delete any pending invitations for this admin office from this company
        Invitation::where('email', $adminOffice->email)
            ->where('company_id', $company->id)
            ->where('status', 'pending')
            ->delete();

        return redirect()->route('employer.admin-offices')->with('success', 'Toegang van administratiekantoor is ingetrokken.');
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
        $company = auth()->user()->company; // For branding
        
        return view('employer.EmployerEmployeeDocuments', compact('documents', 'employee', 'company'));
    }
}
