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
            <x-registration.step-progress :currentStep="3" :showPaymentStep="true" />

            <!-- Main Card -->
            <div class="registrationCard">
                <!-- Header Icon -->
                <div class="cardIcon">
                    <div class="cardIconCircle" style="background-color: rgb(209 250 229);">
                        <svg style="color: rgb(16 185 129); width: 1.75rem; height: 1.75rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                </div>

                <h2 class="cardTitle">Voltooi je betaling</h2>
                <p class="cardSubtitle">Laatste stap om je account te activeren</p>

                <x-registration.status-messages />

                <!-- Subscription Details Card -->
                <div class="subscriptionCard">
                    <div class="subscriptionCard__header">
                        <div>
                            <h3 class="subscriptionCard__title">{{ $subscription->name }}</h3>
                            <p class="subscriptionCard__subtitle">Maandelijks abonnement</p>
                        </div>
                        <div class="subscriptionCard__price">
                            <p class="subscriptionCard__priceAmount">€{{ number_format($subscription->price, 2, ',', '.') }}</p>
                            <p class="subscriptionCard__pricePeriod">per maand</p>
                        </div>
                    </div>

                    <div class="subscriptionCard__features">
                        <div class="subscriptionCard__feature">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ $subscription->max_employees }} medewerkers
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
                            <h3 class="paymentCard__title">Veilig betalen met Mollie</h3>
                            <p class="paymentCard__text">
                                Je wordt doorgestuurd naar Mollie om veilig te betalen. Kies je favoriete betaalmethode zoals iDEAL, creditcard of andere opties.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Payment Button -->
                <form method="POST" action="{{ route('payment.start', ['subscription' => $subscription->id]) }}">
                    @csrf
                    <button type="submit" class="formButton formButton--flex">
                        <svg style="width: 1.25rem; height: 1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Betaal €{{ number_format($subscription->price, 2, ',', '.') }} met Mollie
                    </button>
                </form>

                <div class="centerText">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="textLink">
                            Uitloggen
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
