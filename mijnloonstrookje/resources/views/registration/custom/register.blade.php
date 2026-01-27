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

            <!-- Step Progress -->
            <div class="stepProgress">
                <!-- Step 1 - Active -->
                <div class="stepItem">
                    <div class="stepCircle stepCircle--active">
                        1
                    </div>
                    <div class="stepLabel">
                        <p class="stepLabel__primary stepLabel__primary--active">Maak account</p>
                        <p class="stepLabel__secondary">Persoonlijke gegevens</p>
                    </div>
                </div>

                <!-- Line 1-2 -->
                <div class="stepConnector stepConnector--inactive"></div>

                <!-- Step 2 - Inactive -->
                <div class="stepItem">
                    <div class="stepCircle stepCircle--inactive">
                        2
                    </div>
                    <div class="stepLabel">
                        <p class="stepLabel__primary stepLabel__primary--inactive">Verifieer &</p>
                        <p class="stepLabel__primary stepLabel__primary--inactive">beveilig</p>
                        <p class="stepLabel__secondary">Email en 2FA</p>
                    </div>
                </div>

                <!-- Line 2-3 -->
                <div class="stepConnector stepConnector--inactive"></div>

                <!-- Step 3 - Inactive -->
                <div class="stepItem">
                    <div class="stepCircle stepCircle--inactive">
                        3
                    </div>
                    <div class="stepLabel">
                        <p class="stepLabel__primary stepLabel__primary--inactive">Betalen</p>
                        <p class="stepLabel__secondary">Voltooi registratie</p>
                    </div>
                </div>
            </div>

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
                <p class="cardSubtitle">Vul je gegevens in om te beginnen</p>

                @if(session('error'))
                    <div class="statusMessage statusMessage--error">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

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

                    <!-- E-mailadres -->
                    <div class="formGroup">
                        <label for="email" class="formLabel">E-mailadres</label>
                        <div class="inputWrapper">
                            <div class="inputIcon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <input 
                                type="email" 
                                id="email" 
                                value="{{ $invitation->email }}"
                                disabled
                                placeholder="jan@voorbeeld.nl"
                                class="formInput formInput--withIcon formInput--disabled"
                            >
                        </div>
                    </div>

                    @if($invitation->role === 'employer' && $invitation->custom_subscription_id)
                        <!-- Bedrijfsnaam -->
                        <div class="formGroup">
                            <label for="company_name" class="formLabel">Bedrijfsnaam</label>
                            <div class="inputWrapper">
                                <div class="inputIcon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                    class="formInput formInput--withIcon"
                                >
                            </div>
                            @error('company_name')
                                <p class="formError">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- KVK Nummer -->
                        <div class="formGroup">
                            <label for="kvk_number" class="formLabel">KVK Nummer</label>
                            <div class="inputWrapper">
                                <div class="inputIcon">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                    class="formInput formInput--withIcon"
                                >
                            </div>
                            @error('kvk_number')
                                <p class="formError">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif

                    <!-- Wachtwoord -->
                    <div class="formGroup">
                        <label for="password" class="formLabel">Wachtwoord</label>
                        <div class="inputWrapper">
                            <div class="inputIcon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                class="formInput formInput--withIcon"
                            >
                        </div>
                        @error('password')
                            <p class="formError">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bevestig wachtwoord -->
                    <div class="formGroup">
                        <label for="password_confirmation" class="formLabel">Bevestig wachtwoord</label>
                        <div class="inputWrapper">
                            <div class="inputIcon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                class="formInput formInput--withIcon"
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
                    <button type="submit" class="formButton">
                        Ga verder naar verificatie
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
