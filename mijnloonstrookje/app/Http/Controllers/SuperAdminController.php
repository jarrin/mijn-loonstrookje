<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Invoice;

class SuperAdminController extends Controller
{
    public function __construct()
    {
        // Authentication and role verification handled by route middleware
    }

    /**
     * Display the super admin dashboard.
     */
    public function dashboard()
    {
        // Load users together with their company to display on the super admin dashboard
        $users = User::with('company')->get();

        return view('superadmin.SuperAdminDashboard', compact('users'));
    }

    /**
     * Show the form for editing a user.
     */
    public function editUser(User $user)
    {
        $user->load('company');

        return view('superadmin.editUser', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function updateUser(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:255'],
        ]);

        $user->update($validated);

        return redirect()->route('superadmin.dashboard')->with('success', 'Gebruiker bijgewerkt.');
    }

    /**
     * Delete a user (soft delete).
     * Prevent the currently authenticated user from deleting themselves.
     */
    public function destroyUser(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('superadmin.dashboard')->with('error', 'Je kunt jezelf niet verwijderen.');
        }
        
        // Ontkoppel gebruiker van bedrijf, markeer als inactief en als verwijderd
        $user->company_id = null;
        $user->status = 'inactive';
        $user->is_deleted = true;
        $user->save();

        // Soft delete gebruiker (vult deleted_at)
        $user->delete();

        return redirect()->route('superadmin.dashboard')->with('success', 'Gebruiker verwijderd, ontkoppeld van bedrijf en gemarkeerd als inactief.');
    }

    /**
     * Display subscriptions overview.
     */
    public function subscriptions()
    {
        $subscriptions = Subscription::all();
        return view('superadmin.SuperAdminSubs', compact('subscriptions'));
    }

    /**
     * Update the specified subscription in storage.
     */
    public function updateSubscription(Request $request, Subscription $subscription)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'feature_1' => ['nullable', 'string', 'max:255'],
            'feature_2' => ['nullable', 'string', 'max:255'],
            'feature_3' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric'],
            'subscription_plan' => ['required', 'string', 'max:255'],
        ]);

        $subscription->update($data);

        return redirect()->route('superadmin.subscriptions')->with('status', 'Abonnement bijgewerkt.');
    }

    /**
     * Display audit logs.
     */
    public function logs()
    {
        return view('superadmin.SuperAdminLogs');
    }

    /**
     * Display facturation/invoices overview.
     */
    public function facturation()
    {
        $invoices = Invoice::with('company')->orderBy('due_date', 'desc')->get();
        return view('superadmin.SuperAdminFacturation', compact('invoices'));
    }
}
