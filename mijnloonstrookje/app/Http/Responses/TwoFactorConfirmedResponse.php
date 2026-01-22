<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\TwoFactorConfirmedResponse as TwoFactorConfirmedResponseContract;

class TwoFactorConfirmedResponse implements TwoFactorConfirmedResponseContract
{
    public function toResponse($request)
    {
        // Custom subscription flow
        if (session('pending_custom_subscription_id')) {
            return redirect()->route('registration.verify-and-secure')
                ->with('status', 'Tweestapsverificatie succesvol geactiveerd!');
        }

        // Default: terug naar profiel pagina
        return redirect()->back()
            ->with('status', 'Tweestapsverificatie succesvol geactiveerd!');
    }
}
