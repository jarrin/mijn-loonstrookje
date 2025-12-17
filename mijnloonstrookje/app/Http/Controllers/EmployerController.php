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
        
        return view('employer.EmployerDashboard', compact('company', 'employeeCount', 'maxEmployees', 'nextInvoice'));
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
        // Verify user is actually an employer
        if (auth()->user()->role !== 'employer') {
            abort(403, 'Unauthorized access');
        }
        
        // Get employee only if they belong to the employer's company
        $employee = User::where('id', $employeeId)
                       ->where('role', 'employee')
                       ->where('company_id', auth()->user()->company_id)
                       ->firstOrFail();
        
        $documents = collect([
            // (object)['name' => 'Loonstrook Januari 2024', 'type' => 'Loonstrook', 'date' => '2024-01-31'],
            // (object)['name' => 'Loonstrook Februari 2024', 'type' => 'Loonstrook', 'date' => '2024-02-29'],
        ]);
        
        // When you want to use real data instead, replace the above with:
        // $documents = $employee->documents;
        
        // To disable dummy data and show empty table, uncomment:
        // $documents = collect([]);
        
        return view('employer.EmployerEmployeeDocuments', compact('employee', 'documents'));
    }

    /**
     * Display list of administration offices.
     */
    public function adminOffices()
    {
        // Verify user is actually an employer
        if (auth()->user()->role !== 'employer') {
            abort(403, 'Unauthorized access');
        }
        
        // Get all administration offices
        $adminOffices = User::where('role', 'administration_office')->get();
        
        return view('employer.EmployerAdminOfficeList', compact('adminOffices'));
    }

    /**
     * Store a new administration office.
     */
    public function storeAdminOffice(Request $request)
    {
        // Verify user is actually an employer
        if (auth()->user()->role !== 'employer') {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'administration_office',
            'status' => 'active',
        ]);

        return redirect()->route('employer.admin-offices')->with('success', 'Administratiekantoor toegevoegd.');
    }

    /**
     * Update an administration office.
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $adminOffice->id],
            'status' => ['required', 'string', 'in:active,inactive,pending'],
        ]);

        $adminOffice->update($validated);

        return redirect()->route('employer.admin-offices')->with('success', 'Administratiekantoor bijgewerkt.');
    }

    /**
     * Delete an administration office.
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

        // Mark as inactive and deleted
        $adminOffice->status = 'inactive';
        $adminOffice->is_deleted = true;
        $adminOffice->save();
        
        // Soft delete
        $adminOffice->delete();

        return redirect()->route('employer.admin-offices')->with('success', 'Administratiekantoor verwijderd.');
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
        
        return view('employer.EmployerEmployeeDocuments');
    }
}
