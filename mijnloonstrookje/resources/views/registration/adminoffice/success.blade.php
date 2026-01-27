<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registratie gelukt! - Administratiekantoor</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="registrationPage">
    <x-page-background />
    <div class="registrationContainer">
        <div class="registrationWrapper">
            <!-- Header -->
            <div class="registrationHeader">
                <h1 class="registrationTitle">Registratie gelukt!</h1>
                <p class="registrationSubtitle">Je account is succesvol aangemaakt en beveiligd.</p>
            </div>

            <!-- Main Card -->
            <div class="registrationCard registrationCard--success">
                <div class="cardIcon cardIcon--green">
                    <div class="cardIconCircle cardIconCircle--green">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
                <h2 class="cardTitle">Welkom bij Mijn Loonstrookje!</h2>
                <p class="cardSubtitle">Je kunt nu direct aan de slag.</p>
                <a href="{{ route('administration.dashboard') }}" class="formButton">Ga naar dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>
