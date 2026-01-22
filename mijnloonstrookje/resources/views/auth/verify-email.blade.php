<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-mail Verificatie - Mijn Loonstrookje</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-sky-50 min-h-screen">
    <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-xl">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">E-mail verificatie</h1>
                <p class="mt-2 text-gray-500">Bevestig je e-mailadres om door te gaan</p>
            </div>

            <!-- Main Card -->
            <div class="bg-white rounded-2xl shadow-sm p-8">
                <!-- Header Icon -->
                <div class="flex justify-center mb-4">
                    <div class="w-14 h-14 bg-blue-50 rounded-full flex items-center justify-center">
                        <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>

                <h2 class="text-xl font-bold text-gray-900 text-center mb-1">Check je inbox</h2>
                <p class="text-gray-500 text-center mb-8">We hebben een verificatielink gestuurd naar {{ auth()->user()->email ?? 'je e-mailadres' }}</p>

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3">
                        <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <p class="text-sm text-green-600">Een nieuwe verificatielink is verzonden naar je e-mailadres!</p>
                    </div>
                @endif

                <!-- Info Card -->
                <div class="border border-gray-200 rounded-xl p-5 mb-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">Volgende stappen</h3>
                            <p class="text-sm text-gray-500 mt-1">
                                Klik op de link in de e-mail om je account te verifiëren. Controleer ook je spam folder als je de e-mail niet kunt vinden.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Resend Button -->
                <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
                    @csrf
                    <button type="submit" class="w-full py-3 px-4 border-2 border-blue-500 text-blue-500 font-medium rounded-lg hover:bg-blue-50 transition-colors">
                        Verstuur verificatie e-mail opnieuw
                    </button>
                </form>

                <div class="text-center">
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
