<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\TwoFactorConfirmedResponse as TwoFactorConfirmedResponseContract;

class TwoFactorConfirmedResponse implements TwoFactorConfirmedResponseContract
{
    public function toResponse($request)
    {
        $user = auth()->user();
        
        // Store recovery codes in session so they can be displayed
        $recoveryCodes = $user->recoveryCodes();
        session(['two-factor.recovery-codes' => $recoveryCodes]);
        
        // If coming from profile settings, redirect back there
        if ($request->session()->get('two_factor_from_settings')) {
            $request->session()->forget('two_factor_from_settings');
            return redirect()->route('profile.settings')
                ->with('status', 'Tweestapsverificatie succesvol geactiveerd!');
        }
        
        // Custom subscription flow
        if (session('pending_custom_subscription_id')) {
            return redirect()->route('registration.verify-and-secure')
                ->with('status', 'Tweestapsverificatie succesvol geactiveerd!');
        }
        
        // Employer flow
        if ($user && $user->role === 'employer') {
            return redirect()->route('employer.verify-and-secure')
                ->with('status', 'Tweestapsverificatie succesvol geactiveerd!');
        }
        
        // Employee flow
        if ($user && $user->role === 'employee') {
            return redirect()->route('employee.verify-and-secure')
                ->with('status', 'Tweestapsverificatie succesvol geactiveerd!');
        }

        // Default: terug naar profiel pagina
        return redirect()->route('profile.settings')
            ->with('status', 'Tweestapsverificatie succesvol geactiveerd!');
    }
}
