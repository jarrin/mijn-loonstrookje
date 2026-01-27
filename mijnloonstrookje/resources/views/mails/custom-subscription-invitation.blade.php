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
        .subscription-details {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
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
        <h1>Uitnodiging voor Mijn Loonstrookje - Custom Abonnement</h1>
    </div>
    <div class="content">
        <p>Hallo,</p>
        <p>Je bent uitgenodigd om een account aan te maken op het Mijn Loonstrookje platform met een speciaal custom abonnement.</p>
        <div class="subscription-details">
            <h3 style="color: #333; margin-top: 0;">Jouw Custom Abonnement</h3>
            <p style="color: #555; margin: 5px 0;">
                <strong>Prijs:</strong> €{{ number_format($customSubscription->price, 2, ',', '.') }}
            </p>
            <p style="color: #555; margin: 5px 0;">
                <strong>Betalingstermijn:</strong> {{ ucfirst($customSubscription->billing_period) }}
            </p>
            <p style="color: #555; margin: 5px 0;">
                <strong>Max gebruikers:</strong> {{ $customSubscription->max_users }}
            </p>
        </div>
        <p>Om je account te activeren en aan de slag te gaan, klik je op de onderstaande knop:</p>
        <div style="text-align: center;">
            <a href="{{ $activationUrl }}" class="button">Activeer Mijn Account</a>
        </div>
        <p style="font-size: 14px;">
            Of kopieer deze link naar je browser:<br>
            <a href="{{ $activationUrl }}" style="color: #2563eb; word-break: break-all;">{{ $activationUrl }}</a>
        </p>
        <p>Na activatie doorloop je de volgende stappen:</p>
        <ol>
            <li>Account aanmaken</li>
            <li>E-mailadres verifiëren</li>
            <li>Twee-factor authenticatie instellen</li>
            <li>Betaling voltooien voor jouw custom abonnement</li>
        </ol>
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
