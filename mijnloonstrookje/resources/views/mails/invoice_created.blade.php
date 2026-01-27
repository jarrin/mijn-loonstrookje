<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Nieuwe factuur beschikbaar</title>
</head>
<body>
    <h2>Er is een nieuwe factuur voor uw bedrijf!</h2>
    <p>Factuurnummer: <strong>{{ $invoice->invoice_number }}</strong></p>
    <p>Bedrag: <strong>€{{ number_format($invoice->amount, 2, ',', '.') }}</strong></p>
    <p>Omschrijving: {{ $invoice->description }}</p>
    <p>Vervaldatum: {{ \Carbon\Carbon::parse($invoice->due_date)->format('d-m-Y') }}</p>
    <p>U kunt de factuur bekijken en betalen via het werkgeversportaal.</p>
</body>
</html>
