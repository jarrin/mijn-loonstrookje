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
                <p class="mt-2 text-gray-500">Voltooi de stappen om je account te activeren</p>
            </div>

            <!-- Step Progress -->
            <div class="flex items-center justify-center mb-10">
                <!-- Step 1 - Completed -->
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center font-semibold text-sm">
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
                <div class="w-24 h-0.5 bg-blue-500 mx-2 -mt-6"></div>

                <!-- Step 2 - Completed -->
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center font-semibold text-sm">
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
                <div class="w-24 h-0.5 bg-blue-500 mx-2 -mt-6"></div>

                <!-- Step 3 - Active -->
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center font-semibold text-sm ring-4 ring-blue-100">
                        3
                    </div>
                    <div class="mt-2 text-center">
                        <p class="text-sm font-semibold text-blue-500">Betalen</p>
                        <p class="text-xs text-gray-400">Voltooi registratie</p>
                    </div>
                </div>
            </div>

            <!-- Main Card -->
            <div class="bg-white rounded-2xl shadow-sm p-8">
                <!-- Header Icon -->
                <div class="flex justify-center mb-4">
                    <div class="w-14 h-14 bg-emerald-100 rounded-full flex items-center justify-center">
                        <svg class="w-7 h-7 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                </div>

                <h2 class="text-xl font-bold text-gray-900 text-center mb-1">Voltooi je betaling</h2>
                <p class="text-gray-500 text-center mb-8">Laatste stap om je account te activeren</p>

                <!-- Subscription Details Card -->
                <div class="border border-gray-200 rounded-xl p-5 mb-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-semibold text-gray-900">Custom Abonnement</h3>
                            <p class="text-sm text-gray-500">{{ ucfirst($customSubscription->billing_period) }} abonnement</p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-blue-500">€{{ number_format($customSubscription->price, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-400">per {{ $customSubscription->billing_period == 'maandelijks' ? 'maand' : 'jaar' }}</p>
                        </div>
                    </div>

                    <div class="mt-4 space-y-2">
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ $customSubscription->max_users }} gebruikers
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Onbeperkt documenten uploaden
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Veilige opslag
                        </div>
                    </div>
                </div>

                <!-- iDEAL Payment Card -->
                <div class="border border-gray-200 rounded-xl p-5 mb-6">
                    <div class="flex items-start gap-4">
                        <div class="w-16 h-10 bg-gradient-to-r from-pink-500 to-pink-400 rounded-lg flex items-center justify-center flex-shrink-0">
                            <span class="text-white font-bold text-xs">iDEAL</span>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">Veilig betalen met iDEAL</h3>
                            <p class="text-sm text-gray-500 mt-1">
                                Je wordt doorgestuurd naar Mollie om veilig te betalen met iDEAL. Kies je eigen bank en voltooi de betaling in je vertrouwde bankieromgeving.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-6 mt-4 text-xs text-gray-500">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            SSL beveiligd
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Direct bevestiging
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Gratis transactie
                        </div>
                    </div>
                </div>

                <!-- Bottom Navigation -->
                <div class="flex gap-4">
                    <form method="POST" action="{{ route('payment.start.custom', ['customSubscription' => $customSubscription->id]) }}" class="flex-1">
                        @csrf
                        <button type="submit" 
                                class="w-full inline-flex justify-center items-center px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors cursor-pointer">
                            Betaal €{{ number_format($customSubscription->price, 0, ',', '.') }} via iDEAL
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Footer note -->
                <p class="text-center text-xs text-gray-400 mt-4">
                    🔒 Na succesvolle betaling wordt je account direct geactiveerd
                </p>
            </div>
        </div>
    </div>
</body>
</html>
