<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Registratie - Mijn Loonstrookje</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="registrationPage">
    <div class="registrationContainer">
        <div class="registrationWrapper">
            <!-- Header -->
            <div class="registrationHeader">
                <h1 class="registrationTitle">Account registratie</h1>
                <p class="registrationSubtitle">Gefeliciteerd! Je account is klaar</p>
            </div>

            <!-- Step Progress - All completed -->
            <!-- Step Progress - All completed -->
            <div class="stepProgress">
                <!-- Step 1 - Completed -->
                <div class="stepItem">
                    <div class="stepCircle stepCircle--completed-success">
                        <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="stepLabel">
                        <p class="stepLabel__primary stepLabel__primary--completed">Maak account</p>
                        <p class="stepLabel__secondary">Persoonlijke gegevens</p>
                    </div>
                </div>

                <div class="stepConnector stepConnector--completed-success"></div>

                <!-- Step 2 - Completed -->
                <div class="stepItem">
                    <div class="stepCircle stepCircle--completed-success">
                        <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="stepLabel">
                        <p class="stepLabel__primary stepLabel__primary--completed">Verifieer &</p>
                        <p class="stepLabel__primary stepLabel__primary--completed">beveilig</p>
                        <p class="stepLabel__secondary">Email en 2FA</p>
                    </div>
                </div>

                <div class="stepConnector stepConnector--completed-success"></div>

                <!-- Step 3 - Completed -->
                <div class="stepItem">
                    <div class="stepCircle stepCircle--completed-success">
                        <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="stepLabel">
                        <p class="stepLabel__primary stepLabel__primary--completed">Betalen</p>
                        <p class="stepLabel__secondary">Voltooi registratie</p>
                    </div>
                </div>
            </div>

            <!-- Main Card -->
            <div class="registrationCard">
                <!-- Header Icon -->
                <div class="cardIcon">
                    <div class="successIcon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>

                <h2 class="successTitle">Betaling geslaagd!</h2>
                <p class="successSubtitle">Je account is succesvol geactiveerd</p>

                <div style="text-align: center;">
                    @if($subscription)
                        <div class="successCard--blue">
                            <div class="successCard__header">
                                <div class="successCard__icon successCard__icon--blue">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div class="successCard__body">
                                    <h4 class="successCard__name">{{ $subscription->name }}</h4>
                                    <div style="display: flex; flex-direction: column; gap: 0.25rem; font-size: 0.875rem; color: rgb(30 58 138);">
                                        <p><strong>Prijs:</strong> €{{ number_format($subscription->price, 2, ',', '.') }} per maand</p>
                                        <p><strong>Medewerkers:</strong> Tot {{ $subscription->max_employees }} medewerkers</p>
                                        <p><strong>Status:</strong> <span style="color: rgb(22 163 74); font-weight: 500;">✓ Actief</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif(isset($customSubscription))
                        <div class="successCard--purple">
                            <div class="successCard__header">
                                <div class="successCard__icon successCard__icon--purple">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div class="successCard__body">
                                    <h4 class="successCard__name">Custom Abonnement</h4>
                                    <div style="display: flex; flex-direction: column; gap: 0.25rem; font-size: 0.875rem; color: rgb(88 28 135);">
                                        <p><strong>Prijs:</strong> €{{ number_format($customSubscription->price, 2, ',', '.') }} per {{ $customSubscription->billing_period }}</p>
                                        <p><strong>Max gebruikers:</strong> {{ $customSubscription->max_users }} gebruikers</p>
                                        <p><strong>Status:</strong> <span style="color: rgb(22 163 74); font-weight: 500;">✓ Actief</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Success Features -->
                    <div class="featuresList">
                        <div class="featuresList__items">
                            <div class="featuresList__item">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Account volledig geactiveerd</span>
                            </div>
                            <div class="featuresList__item">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Twee-factor authenticatie ingeschakeld</span>
                            </div>
                            <div class="featuresList__item">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Betaling succesvol verwerkt</span>
                            </div>
                        </div>
                    </div>

                    <!-- Call to Action -->
                    <a href="{{ route('employer.dashboard') }}" class="formButton" style="display: block; text-align: center;">
                        Ga naar Dashboard →
                    </a>
                    
                    <p class="successFooter">
                        📧 Een bevestigingsmail is verzonden naar {{ auth()->user()->email }}
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <p class="successFooter" style="margin-top: 1.5rem;">
                Vragen? Neem contact op via support@mijnloonstrookje.nl
            </p>
        </div>
    </div>
</body>
</html>
