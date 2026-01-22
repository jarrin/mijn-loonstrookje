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
            <x-registration.step-progress :currentStep="3" :showPaymentStep="true" />

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

                <x-registration.status-messages />

                <!-- Subscription Details Card -->
                <div class="border border-gray-200 rounded-xl p-5 mb-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $subscription->name }}</h3>
                            <p class="text-sm text-gray-500">Maandelijks abonnement</p>
                        </div>
                        <div class="text-right">
                            <p class="text-2xl font-bold text-blue-500">€{{ number_format($subscription->price, 2, ',', '.') }}</p>
                            <p class="text-xs text-gray-400">per maand</p>
                        </div>
                    </div>

                    <div class="mt-4 space-y-2">
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ $subscription->max_employees }} medewerkers
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

                <!-- Payment Button -->
                <form method="POST" action="{{ route('payment.start', ['subscription' => $subscription->id]) }}">
                    @csrf
                    <button type="submit" class="w-full py-3 px-4 bg-emerald-500 hover:bg-emerald-600 text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Betaal €{{ number_format($subscription->price, 2, ',', '.') }} via iDEAL
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-gray-700">
                            Uitloggen
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
