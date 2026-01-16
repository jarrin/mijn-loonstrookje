<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Invitation;
use App\Models\User;
use App\Mail\EmployeeInvitation;
use Carbon\Carbon;

class InvitationController extends Controller
{
    /**
     * Send invitation email to employee
     */
    public function sendInvitation(Request $request)
    {
        if (auth()->user()->role !== 'employer') {
            abort(403, 'Unauthorized access');
        }

        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'E-mailadres is verplicht.',
            'email.email' => 'Voer een geldig e-mailadres in.',
        ]);

        // Check if there's already a pending invitation for this email and company
        $existingInvitation = Invitation::where('email', $request->email)
            ->where('company_id', auth()->user()->company_id)
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->first();

        if ($existingInvitation) {
            return back()->with('error', 'Er is al een actieve uitnodiging voor dit e-mailadres voor jouw bedrijf.');
        }

        // Create invitation
        $invitation = Invitation::create([
            'email' => $request->email,
            'token' => Invitation::generateToken(),
            'employer_id' => auth()->id(),
            'invited_by' => auth()->id(),
            'company_id' => auth()->user()->company_id,
            'role' => 'employee',
            'invitation_type' => 'new_account',
            'status' => 'pending',
            'expires_at' => Carbon::now()->addDays(7),
        ]);

        // Get employer and company details
        $employer = auth()->user();
        $companyName = $employer->company ? $employer->company->name : 'Uw Bedrijf';

        // Send email
        try {
            Mail::to($request->email)->send(new EmployeeInvitation($invitation, $employer->name, $companyName));
            
            return redirect()->route('employer.employees')
                ->with('success', 'Uitnodiging succesvol verzonden naar ' . $request->email);
        } catch (\Exception $e) {
            // Delete invitation if email fails
            $invitation->delete();
            
            return back()
                ->withInput()
                ->with('error', 'Er is een fout opgetreden bij het verzenden van de uitnodiging: ' . $e->getMessage());
        }
    }

    /**
     * Show the registration form for invited user (employee or admin office)
     */
    public function acceptInvitation($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        // Check if invitation is valid
        if (!$invitation->isValid()) {
            return redirect()->route('home')
                ->with('error', 'Deze uitnodiging is verlopen of al gebruikt.');
        }

        // Check if this is for an existing user (company_access type)
        if ($invitation->invitation_type === 'company_access') {
            // For existing users, show login page to accept invitation
            return view('auth.accept-invitation', compact('invitation'));
        }

        // Store custom subscription info in session if applicable
        if ($invitation->invitation_type === 'custom_subscription_invite' && $invitation->custom_subscription_id) {
            session(['pending_custom_subscription_id' => $invitation->custom_subscription_id]);
        }

        // For new account invitations, if user is logged in, log them out first
        if (auth()->check()) {
            auth()->logout();
            return redirect()->route('invitation.accept', $token)
                ->with('info', 'Je bent uitgelogd. Maak nu je nieuwe account aan.');
        }

        return view('auth.register-invited', compact('invitation'));
    }

    /**
     * Login and accept invitation
     */
    public function loginAndAcceptInvitation(Request $request, $token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        // Check if invitation is still valid
        if (!$invitation->isValid()) {
            return redirect()->route('home')
                ->with('error', 'Deze uitnodiging is verlopen of al gebruikt.');
        }

        // Validate login credentials
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'E-mailadres is verplicht.',
            'email.email' => 'Voer een geldig e-mailadres in.',
            'password.required' => 'Wachtwoord is verplicht.',
        ]);

        // Check if email matches invitation
        if ($request->email !== $invitation->email) {
            return back()
                ->withInput()
                ->withErrors(['email' => 'Je moet inloggen met het e-mailadres waarnaar de uitnodiging is verzonden: ' . $invitation->email]);
        }

        // Attempt to authenticate
        $credentials = $request->only('email', 'password');
        
        if (auth()->attempt($credentials)) {
            $request->session()->regenerate();
            
            // User is now authenticated, accept the invitation
            return $this->acceptCompanyAccess($invitation);
        }

        return back()
            ->withInput()
            ->withErrors(['password' => 'De inloggegevens zijn onjuist.']);
    }

    /**
     * Accept company access for existing admin office user
     */
    private function acceptCompanyAccess($invitation)
    {
        $user = auth()->user();
        $company = $invitation->company;
        
        // Check if company exists
        if (!$company) {
            return redirect()->route('home')
                ->with('error', 'Het bedrijf waarvoor deze uitnodiging is verstuurd, bestaat niet meer.');
        }
        
        // Check if relationship already exists
        if (!$user->companies()->where('company_id', $company->id)->exists()) {
            // Create the relationship
            $user->companies()->attach($company->id, [
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // Delete any old invitations for this email+company to avoid unique constraint issues
        Invitation::where('email', $invitation->email)
            ->where('company_id', $company->id)
            ->where('id', '!=', $invitation->id)
            ->delete();
        
        // Mark invitation as accepted
        $invitation->update(['status' => 'accepted']);
        
        $companyName = $company->name;
        
        return redirect()->route('administration.dashboard')
            ->with('success', "Je hebt nu toegang tot $companyName!");
    }

    /**
     * Register the invited user (employee or admin office)
     */
    public function registerInvitedEmployee(Request $request, $token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        // Check if invitation is still valid
        if (!$invitation->isValid()) {
            return redirect()->route('home')
                ->with('error', 'Deze uitnodiging is verlopen of al gebruikt.');
        }

        $validation = [
        'name' => 'required|string|max:255',
        'password' => 'required|string|min:8|confirmed',
    ];
    
    $messages = [
        'name.required' => 'Naam is verplicht.',
        'password.required' => 'Wachtwoord is verplicht.',
        'password.min' => 'Wachtwoord moet minimaal 8 tekens lang zijn.',
        'password.confirmed' => 'Wachtwoorden komen niet overeen.',
    ];
    
    // Add KVK and company name validation for employers with custom subscriptions
    if ($invitation->role === 'employer' && $invitation->custom_subscription_id) {
        $validation['company_name'] = 'required|string|max:255';
        $validation['kvk_number'] = 'required|string|size:8|regex:/^[0-9]{8}$/';
        
        $messages['company_name.required'] = 'Bedrijfsnaam is verplicht.';
        $messages['company_name.max'] = 'Bedrijfsnaam mag maximaal 255 tekens zijn.';
        $messages['kvk_number.required'] = 'KVK nummer is verplicht.';
        $messages['kvk_number.size'] = 'KVK nummer moet exact 8 cijfers zijn.';
        $messages['kvk_number.regex'] = 'KVK nummer mag alleen cijfers bevatten.';
    }
    
    $request->validate($validation, $messages);

    // Determine the role from invitation
    $role = $invitation->role ?? 'employee';
    
    // Check if user already exists with this email (including soft-deleted)
    $existingUser = User::withTrashed()->where('email', $invitation->email)->first();
    
    if ($existingUser) {
        // If user was soft-deleted, restore them
        if ($existingUser->trashed()) {
            $existingUser->restore();
        }
        
        // User already exists - just log them in and proceed with the invitation flow
        Auth::login($existingUser);
        $user = $existingUser;
    } else {
        // Create the user (without email_verified_at so verification is required)
        $user = User::create([
            'name' => $request->name,
            'email' => $invitation->email,
            'password' => Hash::make($request->password),
            'role' => $role,
            'company_id' => $role === 'employee' ? $invitation->company_id : null,
            'status' => 'active',
        ]);
    }

    // If this is an employer with a custom subscription, create a company
    if ($role === 'employer' && $invitation->custom_subscription_id) {
        $company = \App\Models\Company::create([
            'name' => $request->company_name,
            'kvk_number' => $request->kvk_number,
            'custom_subscription_id' => null, // Will be set after payment
        ]);
        
        $user->company_id = $company->id;
        $user->save();
        
        // Keep custom subscription ID in session for payment flow
        session(['pending_custom_subscription_id' => $invitation->custom_subscription_id]);
    }    

        // If this is an admin office, create the relationship with the company
        if ($role === 'administration_office' && $invitation->company_id) {
            $user->companies()->attach($invitation->company_id, [
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Mark invitation as accepted
        $invitation->update(['status' => 'accepted']);

        // Log the user in (this triggers automatic email verification sending)
        auth()->login($user);
        
        // Send email verification notification automatically
        $user->sendEmailVerificationNotification();

        // Redirect to verification notice page
        return redirect()->route('verification.notice')
            ->with('success', 'Account succesvol aangemaakt! Check je inbox voor de verificatie email.');
    }

    /**
     * Delete an invitation
     * Only the user who sent the invitation can delete it
     */
    public function deleteInvitation($id)
    {
        $invitation = Invitation::findOrFail($id);

        // Check if the current user is the one who sent the invitation
        if ($invitation->invited_by !== auth()->id()) {
            abort(403, 'Je hebt geen toestemming om deze uitnodiging te verwijderen.');
        }

        // Only allow deleting pending invitations
        if ($invitation->status !== 'pending') {
            return back();
        }

        $invitation->delete();

        return back();
    }
}
