@extends('layout.Layout')

@section('title', 'Facturatie - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="superadmin-page-title">Facturatie Beheer</h1>
    <p class="superadmin-page-subtitle">Hier komt het facturatie overzicht te staan.</p>

    @include('components.TableFilterBar', [
        'filters' => [
            [
                'label' => 'Status',
                'options' => ['Betaald', 'Open', 'Vervallen', 'Geannuleerd']
            ],
            [
                'label' => 'Periode',
                'options' => ['Deze maand', 'Vorige maand', 'Dit kwartaal', 'Dit jaar']
            ],
            [
                'label' => 'Sorteer op',
                'options' => ['Nieuwste eerst', 'Oudste eerst', 'Bedrag oplopend', 'Bedrag aflopend']
            ]
        ]
    ])
    
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
                        <td>
                            @php
                                $statusColors = match($invoice->status) {
                                    'paid' => ['bg' => 'rgba(4, 211, 0, 0.3)', 'text' => '#00BC0D', 'label' => 'Betaald'],
                                    'pending' => ['bg' => 'rgba(255, 132, 0, 0.3)', 'text' => '#FF8400', 'label' => 'Open'],
                                    'overdue' => ['bg' => 'rgba(255, 22, 22, 0.3)', 'text' => '#FF1616', 'label' => 'Vervallen'],
                                    'cancelled' => ['bg' => 'rgba(107, 114, 128, 0.3)', 'text' => '#6B7280', 'label' => 'Geannuleerd'],
                                    default => ['bg' => 'rgba(229, 231, 235, 0.3)', 'text' => '#4B5563', 'label' => ucfirst($invoice->status)]
                                };
                            @endphp
                            <span class="superadmin-log-badge" style="background-color: {{ $statusColors['bg'] }}; color: {{ $statusColors['text'] }};">
                                {{ $statusColors['label'] }}
                            </span>
                        </td>
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
