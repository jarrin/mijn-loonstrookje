<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Registratie - Mijn Loonstrookje</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-sky-50 min-h-screen">
    <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-xl">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Account registratie</h1>
                <p class="mt-2 text-gray-500">Gefeliciteerd! Je account is klaar</p>
            </div>

            <!-- Step Progress - All completed -->
            <div class="flex items-center justify-center mb-10">
                <!-- Step 1 - Completed -->
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center font-semibold text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="mt-2 text-center">
                        <p class="text-sm font-medium text-gray-700">Maak account</p>
                        <p class="text-xs text-gray-400">Persoonlijke gegevens</p>
                    </div>
                </div>

                <!-- Line 1-2 - Completed -->
                <div class="w-24 h-0.5 bg-green-500 mx-2 -mt-6"></div>

                <!-- Step 2 - Completed -->
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center font-semibold text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="mt-2 text-center">
                        <p class="text-sm font-medium text-gray-700">Verifieer &</p>
                        <p class="text-sm font-medium text-gray-700">beveilig</p>
                        <p class="text-xs text-gray-400">Email en 2FA</p>
                    </div>
                </div>

                <!-- Line 2-3 - Completed -->
                <div class="w-24 h-0.5 bg-green-500 mx-2 -mt-6"></div>

                <!-- Step 3 - Completed -->
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-green-500 text-white flex items-center justify-center font-semibold text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="mt-2 text-center">
                        <p class="text-sm font-medium text-gray-700">Betalen</p>
                        <p class="text-xs text-gray-400">Voltooi registratie</p>
                    </div>
                </div>
            </div>

            <!-- Main Card -->
            <div class="bg-white rounded-2xl shadow-sm p-8">
                <!-- Header Icon -->
                <div class="flex justify-center mb-4">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-9 h-9 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>

                <h2 class="text-2xl font-bold text-gray-900 text-center mb-1">Betaling geslaagd!</h2>
                <p class="text-gray-500 text-center mb-8">Je account is succesvol geactiveerd</p>

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
                       class="block w-full py-3 px-4 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg text-center transition-colors mb-4">
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
