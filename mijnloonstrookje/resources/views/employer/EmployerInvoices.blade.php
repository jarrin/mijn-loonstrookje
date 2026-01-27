@extends('layout.Layout')

@section('title', 'Facturen - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="employer-page-title">Facturen</h1>
    <p class="employer-page-subtitle">Overzicht van alle facturen van jouw bedrijf.</p>

    @if($invoices->count())
        <table class="employer-invoice-table">
            <thead>
                <tr>
                    <th>Factuurnummer</th>
                    <th>Bedrag</th>
                    <th>Status</th>
                    <th>Uitgiftedatum</th>
                    <th>Vervaldatum</th>
                    <th>Betaald op</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $invoice)
                    <tr>
                        <td>{{ $invoice->invoice_number }}</td>
                        <td>€{{ number_format($invoice->amount, 2, ',', '.') }}</td>
                        <td>
                            @php
                                $statusColors = [
                                    'paid' => 'color: #00BC0D;',
                                    'pending' => 'color: #FF8400;',
                                    'overdue' => 'color: #FF1616;',
                                    'cancelled' => 'color: #6B7280;',
                                ];
                            @endphp
                            <span style="{{ $statusColors[$invoice->status] ?? '' }}">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </td>
                        <td>{{ $invoice->issued_date ? $invoice->issued_date->format('d-m-Y') : '-' }}</td>
                        <td>{{ $invoice->due_date ? $invoice->due_date->format('d-m-Y') : '-' }}</td>
                        <td>
                            @if($invoice->paid_at)
                                {{ $invoice->paid_at->format('d-m-Y H:i') }}
                            @else
                                <span style="color: #9CA3AF;">-</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="employer-alert-info">Er zijn nog geen facturen voor jouw bedrijf.</div>
    @endif
</section>
@endsection
