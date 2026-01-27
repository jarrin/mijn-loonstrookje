@props([
    'emailVerified' => false,
    'refreshUrl' => null
])

@php
    $refreshUrl = $refreshUrl ?? request()->url();
@endphp

<div class="verificationCard {{ $emailVerified ? 'verificationCard--verified' : 'verificationCard--pending' }}">
    <div class="verificationCard__icon {{ $emailVerified ? 'verificationCard__icon--green' : 'verificationCard__icon--blue' }}">
        @if($emailVerified)
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        @else
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
        @endif
    </div>
    <div class="verificationCard__content">
        <h3 class="verificationCard__title">Verifieer je e-mailadres</h3>
        @if($emailVerified)
            <p class="verificationCard__text verificationCard__text--success">✓ Je e-mailadres is geverifieerd</p>
        @else
            <p class="verificationCard__text verificationCard__text--muted">We hebben een verificatielink naar je e-mailadres gestuurd.</p>
            <p class="verificationCard__text verificationCard__text--small">Geen e-mail ontvangen? Check ook je spam folder.</p>

            <div class="verificationCard__actions">
                <a href="{{ $refreshUrl }}" class="formButton--inline">
                    Ik heb geverifieerd
                </a>
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="formButton--inline-secondary">
                        Verstuur opnieuw
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>
