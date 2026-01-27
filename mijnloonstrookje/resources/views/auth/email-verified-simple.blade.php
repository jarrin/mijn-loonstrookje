<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-mail Geverifieerd - Mijn Loonstrookje</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="registrationPage">
    <x-page-background />
    <div class="registrationContainer">
        <div class="registrationWrapper">
            <!-- Main Card -->
            <div class="registrationCard centerText">
                <!-- Success Icon -->
                <div class="successIcon successIcon--green">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>

                <h2 class="cardTitle cardTitle--large">E-mail geverifieerd!</h2>
                <p class="cardSubtitle cardSubtitle--large">
                    Je e-mailadres is succesvol geverifieerd. Je kunt dit venster nu sluiten en teruggaan naar het registratieproces.
                </p>

                <div class="infoBox infoBox--blue">
                    <p class="infoBoxText">
                        💡 <strong>Tip:</strong> Ga terug naar het vorige tabblad om verder te gaan met de registratie.
                    </p>
                </div>

                <button onclick="window.close()" class="formButton formButton--primary">
                    Sluit dit venster
                </button>
            </div>
        </div>
    </div>
</body>
</html>
