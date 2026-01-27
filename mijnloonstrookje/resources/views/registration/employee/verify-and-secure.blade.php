<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Registratie - Mijn Loonstrookje</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="registrationPage">
    <x-page-background />
    <div class="registrationContainer">
        <div class="registrationWrapper">
            <!-- Header -->
            <div class="registrationHeader">
                <h1 class="registrationTitle">Account registratie</h1>
                <p class="registrationSubtitle">Voltooi de stappen om je account te activeren</p>
            </div>

            <!-- Step Progress - alleen 2 stappen voor employee -->
            @php
                $steps = [
                    ['label' => 'Maak account', 'number' => 1],
                    ['label' => 'Verifieer & beveilig', 'number' => 2],
                ];
            @endphp
            <x-registration.step-progress :currentStep="2" :steps="$steps" :showPaymentStep="false" />

            <!-- Main Card -->
            <div class="registrationCard">
                <div class="cardIcon cardIcon--purple">
                    <div class="cardIconCircle cardIconCircle--purple">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                </div>

                <h2 class="cardTitle">Verifieer en beveilig</h2>
                <p class="cardSubtitle">Bevestig je e-mail en activeer tweestapsverificatie</p>

                <x-registration.status-messages />

                @php
                    $user = auth()->user();
                    $emailVerified = $user && $user->hasVerifiedEmail();
                    $has2FASecret = $user && $user->two_factor_secret;
                    $has2FAConfirmed = $user && $user->two_factor_confirmed_at;
                @endphp

                <!-- Email Verification Card -->
                <x-registration.email-verification-card 
                    :emailVerified="$emailVerified" 
                    :refreshUrl="route('employee.verify-and-secure')" 
                />

                <!-- 2FA Card -->
                <x-registration.two-factor-card 
                    :emailVerified="$emailVerified"
                    :has2FASecret="$has2FASecret"
                    :has2FAConfirmed="$has2FAConfirmed"
                    :user="$user"
                />

                <!-- Navigation -->
                @if($emailVerified && $has2FAConfirmed)
                    <a href="{{ route('employee.documents') }}" 
                       class="formButton">
                        Ga naar dashboard
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @else
                    <div class="centerText">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="formButton formButton--secondary">
                                Uitloggen
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
