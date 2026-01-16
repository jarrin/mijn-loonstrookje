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
            // Check of 2FA is ingesteld (VOOR IEDEREEN)
            if (!$user->two_factor_confirmed_at) {
                return redirect()->route('onboarding.setup-2fa')
                    ->with('error', 'Je moet eerst twee-factor authenticatie inschakelen.');
            }
            
            // Alleen voor employer accounts: check abonnement
            // Employees, admin_offices en super_admins hoeven niet te betalen
            if ($user->role === 'employer') {
                // Check of bedrijf een actief abonnement heeft (standard OF custom)
                if ($user->company && !$user->company->subscription_id && !$user->company->custom_subscription_id) {
                    // Check of er een pending custom subscription is
                    if (session()->has('pending_custom_subscription_id')) {
                        return redirect()->route('payment.custom-checkout', [
                            'customSubscription' => session('pending_custom_subscription_id')
                        ])->with('info', 'Je moet eerst je custom abonnement betalen.');
                    }
                    
                    // Check of er een pending subscription is
                    if (session()->has('pending_subscription_id')) {
                        return redirect()->route('payment.checkout', [
                            'subscription' => session('pending_subscription_id')
                        ])->with('info', 'Je moet eerst je abonnement betalen.');
                    }
                    
                    return redirect()->route('website')
                        ->with('error', 'Je hebt geen actief abonnement. Kies een pakket om door te gaan.');
                }
            }
            
            // Employees, administration_office, en super_admin kunnen direct door
            // Ze hebben geen eigen abonnement nodig
        }

        return $next($request);
    }
}
