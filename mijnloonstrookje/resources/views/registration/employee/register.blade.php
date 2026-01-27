<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Registratie - Mijn Loonstrookje</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="registrationPage">
    <x-page-background />
    <div class="registrationContainer">
        <div class="registrationWrapper">
            <!-- Header -->
            <div class="registrationHeader">
                <h1 class="registrationTitle">Account registratie</h1>
                <p class="registrationSubtitle">Voltooi de stappen om je account te activeren</p>
            </div>

            <!-- Step Progress - 2 stappen voor employee -->
            @php
                $steps = [
                    ['label' => 'Maak account', 'number' => 1],
                    ['label' => 'Verifieer & beveilig', 'number' => 2],
                ];
            @endphp
            <x-registration.step-progress :currentStep="1" :steps="$steps" :showPaymentStep="false" />

            <!-- Main Card -->
            <div class="registrationCard">
                <!-- Header Icon -->
                <div class="cardIcon cardIcon--blue">
                    <div class="cardIconCircle cardIconCircle--blue">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                </div>

                <h2 class="cardTitle">Maak je account aan</h2>
                <p class="cardSubtitle">Je bent uitgenodigd door {{ $invitation->company->name ?? 'een werkgever' }}</p>

                <x-registration.status-messages />

                <form action="{{ route('invitation.register', $invitation->token) }}" method="POST" class="formContainer">
                    @csrf

                    <!-- Volledige naam -->
                    <div class="formGroup">
                        <label for="name" class="formLabel">Volledige naam</label>
                        <div class="inputWrapper">
                            <div class="inputIcon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                class="formInput formInput--withIcon"
                            >
                        </div>
                        @error('name')
                            <p class="formError">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- E-mailadres (disabled) -->
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
                                class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-lg text-gray-500 bg-gray-50 placeholder-gray-400"
                            >
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Dit e-mailadres kan niet worden gewijzigd</p>
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

                    <!-- Bevestig Wachtwoord -->
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

                    <button type="submit" class="formButton">
                        Account aanmaken
                    </button>
                </form>

                <div class="centerText">
                    <p>
                        Heb je al een account? 
                        <a href="{{ route('login') }}" class="textLink">Log hier in</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
