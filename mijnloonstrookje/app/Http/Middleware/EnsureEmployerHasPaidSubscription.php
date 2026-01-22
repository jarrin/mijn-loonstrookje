<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmployerHasPaidSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            // Voor employer accounts
            if ($user->role === 'employer') {
                // Custom subscription flow: redirect naar verify-and-secure als 2FA of email niet is afgerond
                if (session('pending_custom_subscription_id')) {
                    if (!$user->hasVerifiedEmail() || !$user->two_factor_confirmed_at) {
                        return redirect()->route('registration.verify-and-secure');
                    }
                } else {
                    // Regular employer flow: check 2FA en email verificatie
                    if (!$user->hasVerifiedEmail() || !$user->two_factor_confirmed_at) {
                        return redirect()->route('employer.verify-and-secure');
                    }
                }
                
                // Check of bedrijf een actief abonnement heeft (regulier OF custom)
                $hasSubscription = $user->company && ($user->company->subscription_id || $user->company->custom_subscription_id);
                
                if ($user->company && !$hasSubscription) {
                    // Custom subscription pending?
                    if (session('pending_custom_subscription_id')) {
                        return redirect()->route('payment.custom-checkout', [
                            'customSubscription' => session('pending_custom_subscription_id')
                        ]);
                    }
                    
                    // Regular subscription pending?
                    if (session('pending_subscription_id')) {
                        return redirect()->route('payment.checkout', [
                            'subscription' => session('pending_subscription_id')
                        ]);
                    }
                    
                    return redirect()->route('website')
                        ->with('error', 'Je hebt geen actief abonnement. Kies een pakket om door te gaan.');
                }
            }
            
            // Voor employee accounts: check 2FA en email verificatie
            if ($user->role === 'employee') {
                if (!$user->hasVerifiedEmail() || !$user->two_factor_confirmed_at) {
                    return redirect()->route('employee.verify-and-secure');
                }
            }
            
            // Administration office en super_admin kunnen direct door
        }

        return $next($request);
    }
}
