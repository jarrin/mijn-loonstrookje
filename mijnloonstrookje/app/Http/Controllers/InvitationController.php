<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Models\Invitation;
use App\Models\User;
use App\Mail\EmployeeInvitation;
use Carbon\Carbon;

class InvitationController extends Controller
{
    /**
     * Show the form to invite a new employee
     */
    public function showInviteForm()
    {
        if (auth()->user()->role !== 'employer') {
            abort(403, 'Unauthorized access');
        }
        
        return view('employer.invite-employee');
    }

    /**
     * Send invitation email to employee
     */
    public function sendInvitation(Request $request)
    {
        \Log::info('=== START: Send Invitation ===');
        \Log::info('Request data:', $request->all());
        \Log::info('Auth user ID:', [auth()->id()]);
        \Log::info('Auth user role:', [auth()->user()->role]);
        
        if (auth()->user()->role !== 'employer') {
            \Log::error('Unauthorized access - user is not employer');
            abort(403, 'Unauthorized access');
        }

        \Log::info('Starting validation...');
        try {
            $request->validate([
                'email' => 'required|email|unique:users,email|unique:invitations,email',
            ], [
                'email.required' => 'E-mailadres is verplicht.',
                'email.email' => 'Voer een geldig e-mailadres in.',
                'email.unique' => 'Dit e-mailadres is al in gebruik of heeft al een uitnodiging ontvangen.',
            ]);
            \Log::info('Validation passed');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed:', $e->errors());
            throw $e;
        }

        // Create invitation
        \Log::info('Creating invitation...');
        try {
            $invitation = Invitation::create([
                'email' => $request->email,
                'token' => Invitation::generateToken(),
                'employer_id' => auth()->id(),
                'company_id' => auth()->user()->company_id,
                'status' => 'pending',
                'expires_at' => Carbon::now()->addDays(7),
            ]);
            \Log::info('Invitation created successfully', ['invitation_id' => $invitation->id]);
        } catch (\Exception $e) {
            \Log::error('Failed to create invitation:', ['error' => $e->getMessage()]);
            return back()
                ->withInput()
                ->with('error', 'Fout bij aanmaken uitnodiging: ' . $e->getMessage());
        }

        // Get employer and company details
        $employer = auth()->user();
        $companyName = $employer->company ? $employer->company->name : 'Uw Bedrijf';
        \Log::info('Employer details:', ['name' => $employer->name, 'company' => $companyName]);

        // Send email
        \Log::info('Attempting to send email to:', [$request->email]);
        try {
            Mail::to($request->email)->send(new EmployeeInvitation($invitation, $employer->name, $companyName));
            \Log::info('Email sent successfully');
            
            return redirect()->route('employer.employees')
                ->with('success', 'Uitnodiging succesvol verzonden naar ' . $request->email);
        } catch (\Exception $e) {
            \Log::error('Failed to send email:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Delete invitation if email fails
            $invitation->delete();
            \Log::info('Invitation deleted due to email failure');
            
            return back()
                ->withInput()
                ->with('error', 'Er is een fout opgetreden bij het verzenden van de uitnodiging: ' . $e->getMessage());
        }
    }

    /**
     * Show the registration form for invited employee
     */
    public function acceptInvitation($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        // Check if invitation is valid
        if (!$invitation->isValid()) {
            return redirect()->route('home')
                ->with('error', 'Deze uitnodiging is verlopen of al gebruikt.');
        }

        return view('auth.register-invited', compact('invitation'));
    }

    /**
     * Register the invited employee
     */
    public function registerInvitedEmployee(Request $request, $token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        // Check if invitation is still valid
        if (!$invitation->isValid()) {
            return redirect()->route('home')
                ->with('error', 'Deze uitnodiging is verlopen of al gebruikt.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Naam is verplicht.',
            'password.required' => 'Wachtwoord is verplicht.',
            'password.min' => 'Wachtwoord moet minimaal 8 tekens lang zijn.',
            'password.confirmed' => 'Wachtwoorden komen niet overeen.',
        ]);

        // Create the user
        $user = User::create([
            'name' => $request->name,
            'email' => $invitation->email,
            'password' => Hash::make($request->password),
            'role' => 'employee',
            'company_id' => $invitation->company_id,
            'status' => 'active',
        ]);

        // Mark invitation as accepted
        $invitation->update(['status' => 'accepted']);

        // Log the user in
        auth()->login($user);

        // Redirect to 2FA setup
        return redirect()->route('profile.two-factor-authentication')
            ->with('success', 'Account succesvol aangemaakt! Stel nu je twee-factor authenticatie in.');
    }
}
