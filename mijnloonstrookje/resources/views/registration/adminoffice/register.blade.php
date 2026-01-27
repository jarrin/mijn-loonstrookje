<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Registratie - Administratiekantoor</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="registrationPage">
    <x-page-background />
    <div class="registrationContainer">
        <div class="registrationWrapper">
            <!-- Header -->
            <div class="registrationHeader">
                <h1 class="registrationTitle">Account aanmaken</h1>
                <p class="registrationSubtitle">Vul je gegevens in om een administratiekantoor-account aan te maken</p>
            </div>

            <!-- Main Card -->
            <div class="registrationCard">
                <form method="POST" action="{{ route('adminoffice.register') }}">
                    @csrf
                    <div class="formGroup">
                        <label for="name" class="formLabel">Naam</label>
                        <input id="name" name="name" type="text" class="formInput" required autofocus>
                    </div>
                    <div class="formGroup">
                        <label for="email" class="formLabel">E-mailadres</label>
                        <input id="email" name="email" type="email" class="formInput" required>
                    </div>
                    <div class="formGroup">
                        <label for="password" class="formLabel">Wachtwoord</label>
                        <input id="password" name="password" type="password" class="formInput" required>
                    </div>
                    <div class="formGroup">
                        <label for="password_confirmation" class="formLabel">Bevestig wachtwoord</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" class="formInput" required>
                    </div>
                    <button type="submit" class="formButton">Account aanmaken</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
