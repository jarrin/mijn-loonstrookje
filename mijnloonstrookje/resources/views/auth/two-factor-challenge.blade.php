<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twee-factor authenticatie - Mijn Loonstrookje</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="registrationPage">
    <x-page-background />
    <div class="registrationContainer">
        <div class="registrationWrapper">
            <!-- Header -->
            <div class="registrationHeader">
                <h1 class="registrationTitle">Twee-factor authenticatie</h1>
                <p class="registrationSubtitle">Voer je code in om door te gaan</p>
            </div>

            <!-- Main Card -->
            <div class="registrationCard">
                <!-- Header Icon -->
                <div class="cardIcon cardIcon--blue">
                    <div class="cardIconCircle cardIconCircle--blue">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                </div>

                <h2 class="cardTitle">Bevestig je identiteit</h2>
                <p class="cardSubtitle" style="margin-bottom: 1.5rem;">Voer de code uit je authenticator app in</p>

                @if(session('error'))
                    <div class="statusMessage statusMessage--error" style="margin-bottom: 1.5rem;">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('two-factor.login') }}" class="formContainer" id="2fa-form">
                    @csrf

                    <!-- Authentication Code -->
                    <div class="formGroup" id="code-section">
                        <label for="code" class="formLabel">Authenticatiecode</label>
                        <div class="inputWrapper">
                            <div class="inputIcon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                class="formInput formInput--withIcon"
                                style="text-align: center; font-size: 1.25rem; letter-spacing: 0.1em;"
                            >
                        </div>
                        @error('code')
                            <p class="formError">{{ $message }}</p>
                        @enderror
                        <p style="margin-top: 0.25rem; font-size: 0.75rem; color: rgb(107 114 128);">Voer de 6-cijferige code uit je authenticatie-app in</p>
                    </div>

                    <!-- Divider -->
                    <div style="position: relative; margin: 1.25rem 0;" id="divider-section">
                        <div style="position: absolute; inset: 0; display: flex; align-items: center;">
                            <div style="width: 100%; border-top: 1px solid rgb(229 231 235);"></div>
                        </div>
                        <div style="position: relative; display: flex; justify-content: center; font-size: 0.875rem;">
                            <span style="padding: 0 0.5rem; background: white; color: rgb(107 114 128);">of</span>
                        </div>
                    </div>

                    <!-- Recovery Code -->
                    <div class="formGroup" id="recovery-section">
                        <label for="recovery_code" class="formLabel">Herstelcode</label>
                        <div class="inputWrapper">
                            <div class="inputIcon">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                            </div>
                            <input 
                                type="text" 
                                id="recovery_code" 
                                name="recovery_code" 
                                autocomplete="one-time-code"
                                placeholder="abcde-fghij"
                                class="formInput formInput--withIcon"
                            >
                        </div>
                        @error('recovery_code')
                            <p class="formError">{{ $message }}</p>
                        @enderror
                        <p style="margin-top: 0.25rem; font-size: 0.75rem; color: rgb(107 114 128);">Gebruik een herstelcode als je geen toegang hebt tot je app</p>
                    </div>

                    <button type="submit" class="formButton">
                        Verifiëren
                    </button>
                </form>

                <div class="centerText" style="margin-top: 1.5rem;">
                    <p style="font-size: 0.875rem; color: rgb(107 114 128);">
                        <a href="{{ route('login') }}" class="textLink" style="font-weight: 500;">← Terug naar inloggen</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
