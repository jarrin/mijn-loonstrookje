<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\TwoFactorConfirmedResponse as TwoFactorConfirmedResponseContract;

class TwoFactorConfirmedResponse implements TwoFactorConfirmedResponseContract
{
    public function toResponse($request)
    {
        $user = auth()->user();
        
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
        return redirect()->back()
            ->with('status', 'Tweestapsverificatie succesvol geactiveerd!');
    }
}
