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
                <p class="registrationSubtitle">Voltooi de stappen om je account te activeren</p>
            </div>

            <!-- Step Progress -->
            <div class="stepProgress">
                <!-- Step 1 - Completed -->
                <div class="stepItem">
                    <div class="stepCircle stepCircle--completed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="stepLabel">
                        <p class="stepLabel__primary stepLabel__primary--completed">Maak account</p>
                        <p class="stepLabel__secondary">Persoonlijke gegevens</p>
                    </div>
                </div>

                <div class="stepConnector stepConnector--active"></div>

                <!-- Step 2 - Completed -->
                <div class="stepItem">
                    <div class="stepCircle stepCircle--completed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="stepLabel">
                        <p class="stepLabel__primary stepLabel__primary--completed">Verifieer &</p>
                        <p class="stepLabel__primary stepLabel__primary--completed">beveilig</p>
                        <p class="stepLabel__secondary">Email en 2FA</p>
                    </div>
                </div>

                <div class="stepConnector stepConnector--active"></div>

                <!-- Step 3 - Active -->
                <div class="stepItem">
                    <div class="stepCircle stepCircle--active">
                        3
                    </div>
                    <div class="stepLabel">
                        <p class="stepLabel__primary stepLabel__primary--active">Betalen</p>
                        <p class="stepLabel__secondary">Voltooi registratie</p>
                    </div>
                </div>
            </div>

            <!-- Main Card -->
            <div class="registrationCard">
                <!-- Header Icon -->
                <div class="cardIcon cardIcon--blue">
                    <div class="cardIconCircle cardIconCircle--blue">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                </div>

                <h2 class="cardTitle">Voltooi je betaling</h2>
                <p class="cardSubtitle">Laatste stap om je account te activeren</p>

                <!-- Subscription Details Card -->
                <div class="subscriptionCard">
                    <div class="subscriptionCard__header">
                        <div>
                            <h3 class="subscriptionCard__title">Custom Abonnement</h3>
                            <p class="subscriptionCard__subtitle">{{ ucfirst($customSubscription->billing_period) }} abonnement</p>
                        </div>
                        <div class="subscriptionCard__price">
                            <p class="subscriptionCard__priceAmount">€{{ number_format($customSubscription->price, 0, ',', '.') }}</p>
                            <p class="subscriptionCard__pricePeriod">per {{ $customSubscription->billing_period == 'maandelijks' ? 'maand' : 'jaar' }}</p>
                        </div>
                    </div>

                    <div class="subscriptionCard__features">
                        <div class="subscriptionCard__feature">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ $customSubscription->max_users }} gebruikers
                        </div>
                        <div class="subscriptionCard__feature">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Onbeperkt documenten uploaden
                        </div>
                        <div class="subscriptionCard__feature">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Veilige opslag
                        </div>
                    </div>
                </div>

                <!-- iDEAL Payment Card -->
                <div class="paymentCard">
                    <div class="paymentCard__header">
                        <div class="paymentCard__content">
                            <h3 class="paymentCard__title">Veilig betalen via Mollie</h3>
                            <p class="paymentCard__text">
                                Je wordt doorgestuurd naar Mollie om veilig te betalen. Kies je betaalmethode (iDEAL, creditcard, etc.) en voltooi de betaling.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Bottom Navigation -->
                <form method="POST" action="{{ route('payment.start.custom', ['customSubscription' => $customSubscription->id]) }}">
                    @csrf
                    <button type="submit" class="formButton formButton--flex">
                        Betaal €{{ number_format($customSubscription->price, 0, ',', '.') }}
                        <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </button>
                </form>

                <!-- Footer note -->
                <p class="successFooter">
                    🔒 Na succesvolle betaling wordt je account direct geactiveerd
                </p>
            </div>
        </div>
    </div>
</body>
</html>
