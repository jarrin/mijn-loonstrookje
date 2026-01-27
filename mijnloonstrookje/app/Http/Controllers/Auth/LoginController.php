<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Services\AuditLogService;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.Login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Log the login action
            AuditLogService::logLogin($user->id, $user->company_id);
            
            // Redirect based on role
            return $this->redirectBasedOnRole($user);
        }

        return back()->withErrors([
            'email' => 'De opgegeven inloggegevens zijn onjuist.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Je bent succesvol uitgelogd.');
    }

    private function redirectBasedOnRole($user)
    {
        // Check of er een intended subscription in de sessie staat
        if (session()->has('intended_subscription')) {
            $subscriptionId = session()->pull('intended_subscription');
            return redirect()->route('payment.start', ['subscription' => $subscriptionId]);
        }

        // Check registration completion for employers and employees
        if (in_array($user->role, ['employer', 'employee'])) {
            // Check if email is not verified or 2FA is not set up (skip for test accounts)
            if (!$user->isTestAccount() && (!$user->hasVerifiedEmail() || !$user->two_factor_confirmed_at)) {
                // Redirect to the verify-and-secure step based on role
                if ($user->role === 'employer') {
                    // Check if they have a pending custom subscription
                    if (session('pending_custom_subscription_id')) {
                        return redirect()->route('registration.verify-and-secure');
                    }
                    return redirect()->route('employer.verify-and-secure');
                } elseif ($user->role === 'employee') {
                    return redirect()->route('employee.verify-and-secure');
                }
            }
            
            // For employers: Check if they have completed payment (if needed)
            if ($user->role === 'employer') {
                // If they have a pending subscription in session, redirect to payment
                if (session('pending_subscription_id')) {
                    return redirect()->route('payment.checkout', ['subscription' => session('pending_subscription_id')]);
                }
                
                // If they have a pending custom subscription, redirect to custom payment
                if (session('pending_custom_subscription_id')) {
                    return redirect()->route('payment.custom-checkout', ['customSubscription' => session('pending_custom_subscription_id')]);
                }
            }
        }

        // Normal redirect based on role if registration is complete
        if (isset($user->role)) {
            switch ($user->role) {
                case 'super_admin':
                    return redirect()->route('superadmin.dashboard');
                case 'administration_office':
                    return redirect()->route('administration.dashboard');
                case 'employer':
                    return redirect()->route('employer.dashboard');
                case 'employee':
                default:
                    return redirect()->route('employee.documents');
            }
        }
        
        // Default redirect if no role is set
        return redirect()->route('employee.documents');
    }
}