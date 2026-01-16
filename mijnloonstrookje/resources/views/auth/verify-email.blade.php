<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-mail Verificatie - Mijn Loonstrookje</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Verifieer je e-mailadres
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Stap 1 van 3: Bevestig je e-mailadres
                </p>
            </div>

            <!-- Progress bar -->
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-blue-600 h-2.5 rounded-full" style="width: 33%"></div>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="rounded-md bg-green-50 p-4 border border-green-200">
                    <div class="flex">
                        <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="ml-3 text-sm text-green-800">
                            Een nieuwe verificatielink is verzonden naar je e-mailadres!
                        </p>
                    </div>
                </div>
            @endif

            <div class="bg-white shadow-lg rounded-lg p-8">
                <div class="text-center mb-6">
                    <svg class="mx-auto h-12 w-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>

                <h3 class="text-lg font-medium text-gray-900 mb-4 text-center">Check je inbox</h3>
                
                <div class="mb-6">
                    <p class="text-sm text-gray-600 mb-4">
                        Bedankt voor je registratie! We hebben zojuist een verificatielink naar je e-mailadres gestuurd.
                    </p>
                    
                    <p class="text-sm text-gray-600 mb-6">
                        Klik op de link in de e-mail om je account te verifiëren en door te gaan naar de volgende stap.
                    </p>

                    @if(session('pending_custom_subscription_id'))
                        <a href="{{ route('payment.custom-checkout', ['customSubscription' => session('pending_custom_subscription_id')]) }}" 
                           class="block w-full py-3 px-4 bg-purple-600 hover:bg-purple-700 text-white rounded font-medium text-center mb-4">
                            Ga naar betaling
                        </a>
                    @elseif(session('pending_subscription_id'))
                        <a href="{{ route('payment.checkout', ['subscription' => session('pending_subscription_id')]) }}" 
                           class="block w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded font-medium text-center mb-4">
                            Ga naar betaling
                        </a>
                    @else
                        <a href="{{ route('employer.dashboard') }}" 
                           class="block w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded font-medium text-center mb-4">
                            Ga naar dashboard
                        </a>
                    @endif
                </div>

                <div class="border-t border-gray-200 pt-6">
                    <p class="text-xs text-gray-500 mb-4 text-center">
                        Geen e-mail ontvangen? Check ook je spam folder.
                    </p>
                    
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" 
                                class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Verstuur verificatie e-mail opnieuw
                        </button>
                    </form>
                </div>
            </div>

            <div class="text-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">
                        Uitloggen
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
