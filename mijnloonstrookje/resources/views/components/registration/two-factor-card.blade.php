@props([
    'emailVerified' => false,
    'has2FASecret' => false,
    'has2FAConfirmed' => false,
    'user' => null
])

@php
    $user = $user ?? auth()->user();
@endphp

<div class="verificationCard {{ !$emailVerified ? 'verificationCard--disabled' : ($has2FAConfirmed ? 'verificationCard--verified' : 'verificationCard--pending') }}">
    <div class="verificationCard__icon {{ $has2FAConfirmed ? 'verificationCard__icon--green' : 'verificationCard__icon--purple' }}">
        @if($has2FAConfirmed)
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        @else
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
            </svg>
        @endif
    </div>
    <div class="verificationCard__content">
        <h3 class="verificationCard__title">Activeer tweestapsverificatie</h3>
        @if($has2FAConfirmed)
            <p class="verificationCard__text verificationCard__text--success">✓ Tweestapsverificatie is geactiveerd</p>
        @elseif(!$emailVerified)
            <p class="verificationCard__text verificationCard__text--muted">Beveilig je account met tweestapsverificatie.</p>
            <p class="verificationCard__text verificationCard__text--small verificationCard__text--italic">Verifieer eerst je e-mailadres</p>
        @elseif($has2FASecret)
            <p class="verificationCard__text verificationCard__text--muted">Scan de QR-code met je authenticator app:</p>
            <div class="verificationCard__qrCode">
                {!! $user->twoFactorQrCodeSvg() !!}
            </div>
            <form method="POST" action="{{ url('/user/confirmed-two-factor-authentication') }}">
                @csrf
                <label class="verificationCard__label">Voer de 6-cijferige code in:</label>
                
                @if($errors->confirmTwoFactorAuthentication->any())
                    <div class="formError" style="margin-bottom: 0.75rem; padding: 0.75rem; background-color: rgb(254 242 242); border: 1px solid rgb(254 202 202); border-radius: 0.5rem;">
                        <p class="formError">{{ $errors->confirmTwoFactorAuthentication->first('code') }}</p>
                    </div>
                @endif
                
                <input type="text" name="code" placeholder="123456" maxlength="6" required autofocus
                       class="verificationCard__input">
                
                <button type="submit" class="formButton" style="margin-top: 1rem;">
                    Bevestig code
                </button>
            </form>
        @else
            <p class="verificationCard__text verificationCard__text--muted">Beveilig je account met tweestapsverificatie via Google Authenticator of Authy.</p>
            <form method="POST" action="{{ url('/user/two-factor-authentication') }}" style="margin-top: 1rem;">
                @csrf
                <button type="submit" class="formButton--inline-purple">
                    2FA activeren
                </button>
            </form>
        @endif
    </div>
</div>
