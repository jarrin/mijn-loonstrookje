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
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #6b7280;
        }
        .detail-value {
            color: #111827;
        }
        .button {
            display: inline-block;
            background-color: #2563eb;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
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
        <h1>Betaling Geslaagd!</h1>
    </div>
    
    <div class="content">
        <p>Beste {{ $user->name }},</p>
        
        <p>Bedankt voor je betaling! Je abonnement bij Mijn Loonstrookje is succesvol geactiveerd.</p>
        
        <div class="subscription-details">
            <h2 style="margin-top: 0;">Abonnement Details</h2>
            
            <div class="detail-row">
                <span class="detail-label">Bedrijf:</span>
                <span class="detail-value">{{ $company->name }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">KVK Nummer:</span>
                <span class="detail-value">{{ $company->kvk_number }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Abonnement:</span>
                <span class="detail-value">{{ $subscription->name }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Prijs:</span>
                <span class="detail-value">€{{ number_format($subscription->price, 2, ',', '.') }} per maand</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Maximaal aantal medewerkers:</span>
                <span class="detail-value">{{ $subscription->max_employees }}</span>
            </div>
            
            <div class="detail-row">
                <span class="detail-label">Startdatum:</span>
                <span class="detail-value">{{ now()->format('d-m-Y') }}</span>
            </div>
        </div>
        
        <p>Je kunt nu direct aan de slag met het beheren van loonstroken voor je medewerkers.</p>
        
        <div style="text-align: center;">
            <a href="{{ route('employer.dashboard') }}" class="button">Ga naar Dashboard</a>
        </div>
        
        <p><strong>Volgende stappen:</strong></p>
        <ul>
            <li>Nodig je medewerkers uit via het dashboard</li>
            <li>Upload loonstroken voor je werknemers</li>
            <li>Beheer je bedrijfsgegevens</li>
        </ul>
    </div>
    
    <div class="footer">
        <p>Dit is een automatisch gegenereerde e-mail. Je hoeft hier niet op te reageren.</p>
        <p>Vragen? Neem contact op via <a href="mailto:support@mijnloonstrookje.nl">support@mijnloonstrookje.nl</a></p>
        <p>&copy; {{ date('Y') }} Mijn Loonstrookje. Alle rechten voorbehouden.</p>
    </div>
</body>
</html>
