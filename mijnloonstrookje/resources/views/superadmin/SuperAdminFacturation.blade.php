@extends('layout.Layout')

@section('title', 'Facturatie - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="superadmin-page-title">Facturatie Beheer</h1>
    <p class="superadmin-page-subtitle">Hier komt het facturatie overzicht te staan.</p>
    
    <table id="super-admin-facturation">
        <thead>
            <th>Bedrijf</th>
            <th>Datum</th>
            <th>Tijd</th>
            <th>Status</th>
            <th>Bedrag</th>
        </thead>
        <tbody>
            @if(isset($invoices) && $invoices->count())
                @foreach($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->company ? $invoice->company->name : 'Onbekend' }}</td>
                        <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('d-m-Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($invoice->created_at)->format('H:i') }}</td>
                        <td>{{ ucfirst($invoice->status) }}</td>
                        <td>€{{ number_format($invoice->amount, 2) }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5">Geen facturen gevonden.</td>
                </tr>
            @endif
        </tbody>
    </table>
</section>
@endsection
