<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomSubscriptionFlowGuard
{
    /**
     * Bepaal de huidige stap in het registratieproces en redirect indien nodig.
     * 
     * Stappen:
     * 1. Account aanmaken (register pagina) - niet ingelogd
     * 2. Email verifiëren + 2FA activeren - ingelogd maar niet verified/2FA
     * 3. Betalen - verified + 2FA maar niet betaald
     * 4. Klaar - redirect naar dashboard
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $pendingCustomSubscription = session('pending_custom_subscription_id');
        
        // Geen pending custom subscription = geen flow guard nodig
        if (!$pendingCustomSubscription) {
            return $next($request);
        }
        
        // Bepaal huidige stap
        $currentStep = $this->determineCurrentStep($user);
        
        // Bepaal vereiste stap voor deze route
        $requiredStep = $this->getRequiredStepForRoute($request);
        
        // Als gebruiker probeert naar een andere stap te gaan, redirect naar juiste stap
        if ($requiredStep !== null && $currentStep !== $requiredStep) {
            return $this->redirectToStep($currentStep, $pendingCustomSubscription);
        }
        
        return $next($request);
    }
    
    private function determineCurrentStep($user): int
    {
        // Niet ingelogd = stap 1
        if (!$user) {
            return 1;
        }
        
        // Ingelogd maar email niet geverifieerd OF 2FA niet geactiveerd = stap 2 (skip for test accounts)
        if (!$user->isTestAccount() && (!$user->hasVerifiedEmail() || !$user->two_factor_confirmed_at)) {
            return 2;
        }
        
        // Email + 2FA afgerond, maar bedrijf heeft geen abonnement = stap 3
        if ($user->company && !$user->company->custom_subscription_id) {
            return 3;
        }
        
        // Alles afgerond = stap 4 (klaar)
        return 4;
    }
    
    private function getRequiredStepForRoute(Request $request): ?int
    {
        $routeName = $request->route()?->getName();
        
        return match($routeName) {
            'registration.verify-and-secure' => 2,
            'payment.custom-checkout' => 3,
            default => null,
        };
    }
    
    private function redirectToStep(int $step, int $customSubscriptionId): Response
    {
        return match($step) {
            1 => redirect()->route('website')->with('info', 'Start je registratie opnieuw.'),
            2 => redirect()->route('registration.verify-and-secure'),
            3 => redirect()->route('payment.custom-checkout', ['customSubscription' => $customSubscriptionId]),
            4 => redirect()->route('employer.dashboard')->with('info', 'Je registratie is al afgerond.'),
            default => redirect()->route('website'),
        };
    }
}
