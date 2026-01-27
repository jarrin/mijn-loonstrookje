<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .header {
            background-color: #2563eb;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background-color: #f9fafb;
            padding: 30px;
            border: 1px solid #e5e7eb;
        }
        .button {
            display: inline-block;
            background-color: #2563eb;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            color: #6b7280;
            font-size: 14px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Uitnodiging voor Mijn Loonstrookje</h1>
    </div>
    <div class="content">
        <p>Hallo,</p>
        <p>Je bent uitgenodigd door <strong>{{ $employerName }}</strong> van <strong>{{ $companyName }}</strong> om als administratiekantoor toegang te krijgen tot hun bedrijfsgegevens op het Mijn Loonstrookje platform.</p>
        <p>Om je account te activeren en je wachtwoord in te stellen, klik je op de onderstaande knop:</p>
        <div style="text-align: center;">
            <a href="{{ $activationUrl }}" class="button">Account Aanmaken</a>
        </div>
        <p style="font-size: 14px;">
            Of kopieer deze link naar je browser:<br>
            <a href="{{ $activationUrl }}" style="color: #2563eb; word-break: break-all;">{{ $activationUrl }}</a>
        </p>
        <p>Deze uitnodiging is <strong>7 dagen</strong> geldig.</p>
        <p style="color: #999; font-size: 12px; margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
            Als je deze uitnodiging niet hebt verwacht, kun je deze e-mail negeren.
        </p>
    </div>
    <div class="footer">
        <p>Dit is een automatisch gegenereerde e-mail. Je hoeft hier niet op te reageren.</p>
        <p>Vragen? Neem contact op via <a href="mailto:support@mijnloonstrookje.nl">support@mijnloonstrookje.nl</a></p>
        <p>&copy; {{ date('Y') }} Mijn Loonstrookje. Alle rechten voorbehouden.</p>
    </div>
</body>
</html>
