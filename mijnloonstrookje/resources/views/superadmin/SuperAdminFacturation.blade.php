@extends('layout.Layout')

@section('title', 'Facturatie - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="superadmin-page-title">Facturatie Beheer</h1>
    <p class="superadmin-page-subtitle">Overzicht van alle facturen in het systeem.</p>

    @include('components.TableFilterBar', [
        'filters' => [
            [
                'label' => 'Status',
                'options' => ['Betaald', 'Open', 'Vervallen', 'Geannuleerd']
            ],
            [
                'label' => 'Abonnement Type',
                'options' => ['Standaard', 'Custom']
            ],
            [
                'label' => 'Sorteer op',
                'options' => ['Nieuwste eerst', 'Oudste eerst', 'Bedrag oplopend', 'Bedrag aflopend']
            ]
        ]
    ])
    
    <table id="super-admin-facturation">
        <thead>
            <th>Factuurnummer</th>
            <th>Bedrijf</th>
            <th>Abonnement</th>
            <th>Bedrag</th>
            <th>Datum</th>
            <th>Status</th>
            <th>Betaald op</th>
        </thead>
        <tbody>
            @if(isset($invoices) && $invoices->count())
                @foreach($invoices as $invoice)
                    <tr>
                        <td><strong>{{ $invoice->invoice_number }}</strong></td>
                        <td>{{ $invoice->company ? $invoice->company->name : 'Onbekend' }}</td>
                        <td>
                            @if($invoice->subscription)
                                <span style="color: #0095FF;">{{ ucfirst($invoice->subscription->subscription_plan) }}</span>
                            @elseif($invoice->customSubscription)
                                <span style="color: #9100EC;">Custom (€{{ number_format($invoice->customSubscription->price, 2, ',', '.') }} {{ $invoice->customSubscription->billing_period }})</span>
                            @else
                                <span style="color: #6B7280;">-</span>
                            @endif
                        </td>
                        <td><strong>€{{ number_format($invoice->amount, 2, ',', '.') }}</strong></td>
                        <td>{{ $invoice->issued_date ? $invoice->issued_date->format('d-m-Y') : '-' }}</td>
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
                        <td>
                            @if($invoice->paid_at)
                                {{ $invoice->paid_at->format('d-m-Y H:i') }}
                            @else
                                <span style="color: #9CA3AF;">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" style="text-align: center; padding: 2rem; color: #6B7280;">
                        Nog geen facturen in het systeem.
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
    
    
</section>
@endsection
