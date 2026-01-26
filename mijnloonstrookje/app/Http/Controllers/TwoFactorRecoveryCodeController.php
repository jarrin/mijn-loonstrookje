<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TwoFactorRecoveryCodeController extends Controller
{
    /**
     * Generate new recovery codes and show them to the user
     */
    public function store(Request $request)
    {
        $user = $request->user();
        
        // Regenerate recovery codes
        $user->forceFill([
            'two_factor_recovery_codes' => encrypt(json_encode(collect(range(1, 8))->map(function () {
                return \Illuminate\Support\Str::random(10) . '-' . \Illuminate\Support\Str::random(10);
            })->all())),
        ])->save();
        
        // Store in session so they can be displayed
        session(['two-factor.recovery-codes' => $user->recoveryCodes()]);
        
        return redirect()->route('profile.settings')
            ->with('success', 'Nieuwe herstelcodes zijn gegenereerd. Bewaar ze op een veilige plaats.');
    }
}
