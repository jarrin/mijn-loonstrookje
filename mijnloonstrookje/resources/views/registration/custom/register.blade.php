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
                <!-- Step 1 - Active -->
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center font-semibold text-sm ring-4 ring-blue-100">
                        1
                    </div>
                    <div class="mt-2 text-center">
                        <p class="text-sm font-semibold text-blue-500">Maak account</p>
                        <p class="text-xs text-gray-400">Persoonlijke gegevens</p>
                    </div>
                </div>

                <!-- Line 1-2 -->
                <div class="w-24 h-0.5 bg-gray-200 mx-2 -mt-6"></div>

                <!-- Step 2 - Inactive -->
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-white border-2 border-gray-200 text-gray-400 flex items-center justify-center font-semibold text-sm">
                        2
                    </div>
                    <div class="mt-2 text-center">
                        <p class="text-sm font-medium text-gray-400">Verifieer &</p>
                        <p class="text-sm font-medium text-gray-400">beveilig</p>
                        <p class="text-xs text-gray-400">Email en 2FA</p>
                    </div>
                </div>

                <!-- Line 2-3 -->
                <div class="w-24 h-0.5 bg-gray-200 mx-2 -mt-6"></div>

                <!-- Step 3 - Inactive -->
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-white border-2 border-gray-200 text-gray-400 flex items-center justify-center font-semibold text-sm">
                        3
                    </div>
                    <div class="mt-2 text-center">
                        <p class="text-sm font-medium text-gray-400">Betalen</p>
                        <p class="text-xs text-gray-400">Voltooi registratie</p>
                    </div>
                </div>
            </div>

            <!-- Main Card -->
            <div class="bg-white rounded-2xl shadow-sm p-8">
                <!-- Header Icon -->
                <div class="flex justify-center mb-4">
                    <div class="w-14 h-14 bg-blue-50 rounded-full flex items-center justify-center">
                        <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                </div>

                <h2 class="text-xl font-bold text-gray-900 text-center mb-1">Maak je account aan</h2>
                <p class="text-gray-500 text-center mb-8">Vul je gegevens in om te beginnen</p>

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm text-red-600">{{ session('error') }}</p>
                    </div>
                @endif

                <form action="{{ route('invitation.register', $invitation->token) }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- Volledige naam -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Volledige naam</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                value="{{ old('name') }}"
                                required
                                autofocus
                                placeholder="Jan Jansen"
                                class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- E-mailadres -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">E-mailadres</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <input 
                                type="email" 
                                id="email" 
                                value="{{ $invitation->email }}"
                                disabled
                                placeholder="jan@voorbeeld.nl"
                                class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-lg text-gray-500 bg-gray-50 placeholder-gray-400"
                            >
                        </div>
                    </div>

                    @if($invitation->role === 'employer' && $invitation->custom_subscription_id)
                        <!-- Bedrijfsnaam -->
                        <div>
                            <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1.5">Bedrijfsnaam</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <input 
                                    type="text" 
                                    id="company_name" 
                                    name="company_name" 
                                    value="{{ old('company_name') }}"
                                    required
                                    placeholder="Mijn Bedrijf BV"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            </div>
                            @error('company_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- KVK Nummer -->
                        <div>
                            <label for="kvk_number" class="block text-sm font-medium text-gray-700 mb-1.5">KVK Nummer</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <input 
                                    type="text" 
                                    id="kvk_number" 
                                    name="kvk_number" 
                                    value="{{ old('kvk_number') }}"
                                    required
                                    maxlength="8"
                                    pattern="[0-9]{8}"
                                    placeholder="12345678"
                                    class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                >
                            </div>
                            @error('kvk_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <!-- Wachtwoord -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Wachtwoord</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                required
                                minlength="8"
                                placeholder="Minimaal 8 tekens"
                                class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bevestig wachtwoord -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Bevestig wachtwoord</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input 
                                type="password" 
                                id="password_confirmation" 
                                name="password_confirmation" 
                                required
                                minlength="8"
                                placeholder="Herhaal je wachtwoord"
                                class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>
                    </div>

                    <!-- Terms checkbox -->
                    <div class="flex items-start">
                        <input 
                            type="checkbox" 
                            id="terms" 
                            name="terms" 
                            required
                            class="mt-1 h-4 w-4 text-blue-500 border-gray-300 rounded focus:ring-blue-500"
                        >
                        <label for="terms" class="ml-2 text-sm text-gray-600">
                            Ik ga akkoord met de <a href="#" class="text-blue-500 hover:underline">algemene voorwaarden</a> en <a href="#" class="text-blue-500 hover:underline">privacyverklaring</a>
                        </label>
                    </div>

                    <!-- Submit button -->
                    <button 
                        type="submit" 
                        class="w-full py-3.5 px-4 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                        Ga verder naar verificatie
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
