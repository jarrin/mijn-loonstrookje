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

        // Alleen voor employer accounts
        if ($user && $user->role === 'employer') {
            // Check of 2FA is ingesteld
            if (!$user->two_factor_confirmed_at) {
                return redirect()->route('onboarding.setup-2fa')
                    ->with('error', 'Je moet eerst twee-factor authenticatie inschakelen.');
            }

            // Check of bedrijf een actief abonnement heeft
            if ($user->company && !$user->company->subscription_id) {
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

        return $next($request);
    }
}
