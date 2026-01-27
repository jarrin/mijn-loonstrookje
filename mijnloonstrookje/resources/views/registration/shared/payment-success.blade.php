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
            <div class="stepProgress">
                <!-- Step 1 - Completed -->
                <div class="stepItem">
                    <div class="stepCircle" style="background-color: rgb(34 197 94); color: white;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="stepLabel">
                        <p class="stepLabel__primary stepLabel__primary--completed">Maak account</p>
                        <p class="stepLabel__secondary">Persoonlijke gegevens</p>
                    </div>
                </div>

                <div class="stepConnector" style="background-color: rgb(34 197 94);"></div>

                <!-- Step 2 - Completed -->
                <div class="stepItem">
                    <div class="stepCircle" style="background-color: rgb(34 197 94); color: white;">
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

                <div class="stepConnector" style="background-color: rgb(34 197 94);"></div>

                <!-- Step 3 - Completed -->
                <div class="stepItem">
                    <div class="stepCircle" style="background-color: rgb(34 197 94); color: white;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <div style="width: 4rem; height: 4rem; background-color: rgb(220 252 231); border-radius: 9999px; display: flex; align-items: center; justify-content: center;">
                        <svg style="width: 2.25rem; height: 2.25rem; color: rgb(22 163 74);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>

                <h2 style="font-size: 1.5rem; font-weight: 700; color: rgb(17 24 39); text-align: center; margin-bottom: 0.25rem;">Betaling geslaagd!</h2>
                <p class="cardSubtitle">Je account is succesvol geactiveerd</p>

                <div class="text-center">
                    @if($subscription)
                        <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-5 mb-6 text-left">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-blue-900 mb-2">{{ $subscription->name }}</h4>
                                    <div class="space-y-1 text-sm text-blue-800">
                                        <p><strong>Prijs:</strong> €{{ number_format($subscription->price, 2, ',', '.') }} per maand</p>
                                        <p><strong>Medewerkers:</strong> Tot {{ $subscription->max_employees }} medewerkers</p>
                                        <p><strong>Status:</strong> <span class="text-green-600 font-medium">✓ Actief</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @elseif(isset($customSubscription))
                        <div class="bg-purple-50 border-2 border-purple-200 rounded-xl p-5 mb-6 text-left">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-purple-900 mb-2">Custom Abonnement</h4>
                                    <div class="space-y-1 text-sm text-purple-800">
                                        <p><strong>Prijs:</strong> €{{ number_format($customSubscription->price, 2, ',', '.') }} per {{ $customSubscription->billing_period }}</p>
                                        <p><strong>Max gebruikers:</strong> {{ $customSubscription->max_users }} gebruikers</p>
                                        <p><strong>Status:</strong> <span class="text-green-600 font-medium">✓ Actief</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Success Features -->
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-6">
                        <div class="space-y-2 text-left text-sm text-gray-700">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Account volledig geactiveerd</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Twee-factor authenticatie ingeschakeld</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span>Betaling succesvol verwerkt</span>
                            </div>
                        </div>
                    </div>

                    <!-- Call to Action -->
                    <a href="{{ route('employer.dashboard') }}" 
                       class="formButton" style="display: block; text-align: center;">
                        Ga naar Dashboard →
                    </a>
                    
                    <p class="text-xs text-gray-500">
                        📧 Een bevestigingsmail is verzonden naar {{ auth()->user()->email }}
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <p class="text-center text-xs text-gray-500 mt-6">
                Vragen? Neem contact op via support@mijnloonstrookje.nl
            </p>
        </div>
    </div>
</body>
</html>
