<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subscription;
use App\Models\CustomSubscription;
use App\Models\Invoice;
use App\Models\Log;
use App\Models\Company;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomSubscriptionInvitation;

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

        return redirect()->route('superadmin.dashboard');
    }

    /**
     * Delete a user (soft delete).
     * Prevent the currently authenticated user from deleting themselves.
     */
    public function destroyUser(User $user)
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('superadmin.dashboard');
        }
        
        // Ontkoppel gebruiker van bedrijf, markeer als inactief en als verwijderd
        $user->company_id = null;
        $user->status = 'inactive';
        $user->is_deleted = true;
        $user->save();

        // Soft delete gebruiker (vult deleted_at)
        $user->delete();

        return redirect()->route('superadmin.dashboard');
    }

    /**
     * Display subscriptions overview.
     */
    public function subscriptions()
    {
        $subscriptions = Subscription::all();

        // Eager load companies with users, and pending invitations for custom subscriptions
        $customSubscriptions = CustomSubscription::withCount('companies')
            ->with(['companies.users', 'invitations' => function($query) {
                $query->where('status', 'pending')
                      ->where('invitation_type', 'custom_subscription_invite')
                      ->where('expires_at', '>', now());
            }])
            ->get();

        return view('superadmin.SuperAdminSubs', compact('subscriptions', 'customSubscriptions'));
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

        return redirect()->route('superadmin.subscriptions');
    }

    /**
     * Store a new custom subscription.
     */
    public function storeCustomSubscription(Request $request)
    {
        $data = $request->validate([
            'price' => ['required', 'numeric', 'min:0'],
            'billing_period' => ['required', 'string', 'in:maandelijks,jaarlijks'],
            'max_users' => ['required', 'integer', 'min:1'],
        ]);

        CustomSubscription::create($data);

        return back();
    }

    /**
     * Update the specified custom subscription.
     */
    public function updateCustomSubscription(Request $request, CustomSubscription $customSubscription)
    {
        $data = $request->validate([
            'price' => ['required', 'numeric', 'min:0'],
            'billing_period' => ['required', 'string', 'in:maandelijks,jaarlijks'],
            'max_users' => ['required', 'integer', 'min:1'],
        ]);

        $customSubscription->update($data);

        return redirect()->route('superadmin.subscriptions');
    }

    /**
     * Delete a custom subscription.
     */
    public function destroyCustomSubscription(CustomSubscription $customSubscription)
    {
        $customSubscription->delete();

        return redirect()->route('superadmin.subscriptions');
    }

    /**
     * Cancel a pending custom subscription invitation.
     */
    public function cancelInvitation(Request $request, $invitationId)
    {
        $invitation = \App\Models\Invitation::findOrFail($invitationId);
        
        // Store custom subscription ID for redirect
        $customSubscriptionId = $invitation->custom_subscription_id;
        
        // Delete the invitation
        $invitation->delete();
        
        return back();
    }

    /**
     * Remove a company from a custom subscription.
     */
    public function removeCompanyFromCustomSubscription(CustomSubscription $customSubscription, Company $company)
    {
        // Check if company actually has this custom subscription
        if ($company->custom_subscription_id !== $customSubscription->id) {
            return back();
        }
        
        // Remove custom subscription from company
        $company->custom_subscription_id = null;
        $company->save();
        
        return back();
    }

    /**
     * Invite an employer with a custom subscription.
     */
    public function inviteCustomSubscription(Request $request, CustomSubscription $customSubscription)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'E-mailadres is verplicht.',
            'email.email' => 'Voer een geldig e-mailadres in.',
        ]);

        // Check if there's already a pending invitation for this email and custom subscription
        $existingInvitation = \App\Models\Invitation::where('email', $request->email)
            ->where('custom_subscription_id', $customSubscription->id)
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->first();

        if ($existingInvitation) {
            return back();
        }

        // Create invitation
        $invitation = \App\Models\Invitation::create([
            'email' => $request->email,
            'token' => \App\Models\Invitation::generateToken(),
            'invited_by' => auth()->id(),
            'custom_subscription_id' => $customSubscription->id,
            'role' => 'employer',
            'invitation_type' => 'custom_subscription_invite',
            'status' => 'pending',
            'expires_at' => \Carbon\Carbon::now()->addDays(7),
        ]);

        // Send email
        try {
            Mail::to($request->email)->send(new CustomSubscriptionInvitation($invitation, $customSubscription));
            
            return back();
        } catch (\Exception $e) {
            // Delete invitation if email fails
            $invitation->delete();
            
            return back()->withInput();
        }
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
