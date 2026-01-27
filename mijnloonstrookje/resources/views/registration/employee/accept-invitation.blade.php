<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uitnodiging Accepteren - Mijn Loonstrookje</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="registrationPage">
    <div class="registrationContainer">
        <div class="registrationWrapper">
            <!-- Header -->
            <div class="registrationHeader">
                <h1 class="registrationTitle">Uitnodiging Accepteren</h1>
                <p class="registrationSubtitle">Log in om de uitnodiging te accepteren</p>
            </div>

            <!-- Main Card -->
            <div class="registrationCard">
                <!-- Header Icon -->
                <div class="cardIcon cardIcon--blue">
                    <div class="cardIconCircle cardIconCircle--blue">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>

                <h2 class="cardTitle">Welkom terug</h2>
                <p class="cardSubtitle" style="margin-bottom: 1.5rem;">Log in om toegang te krijgen tot {{ $invitation->company->name ?? 'het bedrijf' }}</p>

                <x-registration.status-messages />

                <!-- Info message -->
                <div class="statusMessage statusMessage--info">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p>Je bent uitgenodigd om toegang te krijgen. Log in met je bestaande account om de uitnodiging te accepteren.</p>
                </div>

                <form action="{{ route('invitation.login.accept', $invitation->token) }}" method="POST" class="formContainer">
                    @csrf

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
                                value="{{ old('email', $invitation->email) }}"
                                required
                                autofocus
                                placeholder="jouw@email.com"
                                class="formInput formInput--withIcon"
                            >
                        </div>
                        @error('email')
                            <p class="formError">{{ $message }}</p>
                        @enderror
                    </div>

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
                                placeholder="Je wachtwoord"
                                class="formInput formInput--withIcon"
                            >
                        </div>
                        @error('password')
                            <p class="formError">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="formButton">
                        Inloggen en uitnodiging accepteren
                    </button>
                </form>

                <div class="centerText" style="margin-top: 1.5rem;">
                    <p style="font-size: 0.75rem; color: rgb(107 114 128);">
                        Deze uitnodiging is geldig tot {{ $invitation->expires_at->format('d-m-Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
