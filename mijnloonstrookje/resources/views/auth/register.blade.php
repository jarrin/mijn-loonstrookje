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
            <x-registration.step-progress :currentStep="1" :showPaymentStep="true" />

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
                <p class="cardSubtitle" style="margin-bottom: 1.5rem;">Vul je gegevens in om te beginnen</p>

                @if(session('subscription_id'))
                    <!-- <div class="statusMessage statusMessage--info">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p>Je hebt een abonnement gekozen. Maak eerst je account aan.</p>
                    </div> -->
                @endif

                <form method="POST" action="{{ route('register') }}" class="formContainer">
                    @csrf
                    
                    @if(session('subscription_id'))
                        <input type="hidden" name="subscription_id" value="{{ session('subscription_id') }}">
                    @endif


                    <!-- Naam en Bedrijf -->
                    <div class="formRow">
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

                        <!-- Bedrijfsnaam -->
                        <div class="formGroup">
                        <label for="company_name" class="formLabel">Bedrijfsnaam</label>
                        <div class="inputWrapper">
                            <div class="inputIcon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
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
                    </div>

                    <!-- KVK en Email -->
                    <div class="formRow">
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
                                name="email" 
                                value="{{ old('email') }}"
                                required
                                placeholder="jan@voorbeeld.nl"
                                class="formInput formInput--withIcon"
                            >
                        </div>
                        @error('email')
                            <p class="formError">{{ $message }}</p>
                        @enderror
                        </div>
                    </div>

                    <!-- Wachtwoorden -->
                    <div class="formRow">
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

                        <!-- Bevestig Wachtwoord -->
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
                    </div>

                    <button type="submit" class="formButton">
                        Account aanmaken
                    </button>
                </form>

                <div class="centerText" style="margin-top: 1.5rem;">
                    <p style="font-size: 0.875rem; color: rgb(107 114 128);">
                        Heb je al een account? 
                        <a href="{{ route('login') }}" class="textLink" style="font-weight: 500;">Log hier in</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
