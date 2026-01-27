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
        .invoice-details {
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
        <h1>Nieuwe factuur beschikbaar</h1>
    </div>
    <div class="content">
        <p>Beste werkgever,</p>
        <p>Er is een nieuwe factuur voor uw bedrijf aangemaakt. Hieronder vindt u de details:</p>
        <div class="invoice-details">
            <div class="detail-row">
                <span class="detail-label">Factuurnummer:</span>
                <span class="detail-value">{{ $invoice->invoice_number }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Bedrag:</span>
                <span class="detail-value">€{{ number_format($invoice->amount, 2, ',', '.') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Omschrijving:</span>
                <span class="detail-value">{{ $invoice->description }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Vervaldatum:</span>
                <span class="detail-value">{{ \Carbon\Carbon::parse($invoice->due_date)->format('d-m-Y') }}</span>
            </div>
        </div>
        <p>U kunt de factuur bekijken en betalen via het werkgeversportaal.</p>
        <div style="text-align: center;">
            <a href="{{ route('employer.invoices') }}" class="button">Bekijk facturen</a>
        </div>
    </div>
    <div class="footer">
        <p>Dit is een automatisch gegenereerde e-mail. U hoeft hier niet op te reageren.</p>
        <p>Vragen? Neem contact op via <a href="mailto:support@mijnloonstrookje.nl">support@mijnloonstrookje.nl</a></p>
        <p>&copy; {{ date('Y') }} Mijn Loonstrookje. Alle rechten voorbehouden.</p>
    </div>
</body>
</html>
