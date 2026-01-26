<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twee-factor authenticatie - Mijn Loonstrookje</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-sky-50 min-h-screen">
    <x-page-background />
    <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="w-full max-w-md">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Twee-factor authenticatie</h1>
                <p class="mt-2 text-gray-500">Voer je code in om door te gaan</p>
            </div>

            <!-- Main Card -->
            <div class="bg-white rounded-2xl shadow-sm p-8">
                <!-- Header Icon -->
                <div class="flex justify-center mb-4">
                    <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                </div>

                <h2 class="text-xl font-bold text-gray-900 text-center mb-1">Bevestig je identiteit</h2>
                <p class="text-gray-500 text-center mb-8">Voer de code uit je authenticator app in</p>

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-sm text-red-600">{{ session('error') }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('two-factor.login') }}" class="space-y-5" id="2fa-form">
                    @csrf

                    <!-- Authentication Code -->
                    <div id="code-section">
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-1.5">Authenticatiecode</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input 
                                type="text" 
                                id="code" 
                                name="code" 
                                inputmode="numeric"
                                pattern="[0-9]*"
                                maxlength="6"
                                autocomplete="one-time-code"
                                autofocus
                                placeholder="123456"
                                class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-center text-xl tracking-widest"
                            >
                        </div>
                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Voer de 6-cijferige code uit je authenticatie-app in</p>
                    </div>

                    <!-- Divider -->
                    <div class="relative" id="divider-section">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">of</span>
                        </div>
                    </div>

                    <!-- Recovery Code -->
                    <div id="recovery-section">
                        <label for="recovery_code" class="block text-sm font-medium text-gray-700 mb-1.5">Herstelcode</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                            </div>
                            <input 
                                type="text" 
                                id="recovery_code" 
                                name="recovery_code" 
                                autocomplete="one-time-code"
                                placeholder="abcde-fghij"
                                class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>
                        @error('recovery_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Gebruik een herstelcode als je geen toegang hebt tot je app</p>
                    </div>

                    <button type="submit" class="w-full py-3 px-4 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors">
                        Verifiëren
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-700">
                        ← Terug naar inloggen
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
