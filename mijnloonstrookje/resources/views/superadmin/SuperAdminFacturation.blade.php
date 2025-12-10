@extends('layout.Layout')

@section('title', 'Facturatie - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="text-2xl mb-4">Facturatie Beheer</h1>
    <p>Hier komt het facturatie overzicht te staan.</p>
    
    <div class="mt-6 space-x-4">
        <a href="{{ route('superadmin.dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Terug naar Dashboard</a>
        <a href="{{ route('superadmin.subscriptions') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Abonnementen</a>
        <a href="{{ route('superadmin.logs') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Logs</a>
    </div>
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
