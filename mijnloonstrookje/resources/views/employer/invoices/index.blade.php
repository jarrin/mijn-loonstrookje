@extends('layout.Layout')

@section('title', 'Facturen - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="employer-page-title">Facturen</h1>
    <p class="employer-welcome-text">Overzicht van al je facturen en betalingen.</p>
    
    @if($invoices->isEmpty())
        <div style="padding: 2rem; text-align: center; background: white; border-radius: 8px; margin-top: 2rem;">
            <p style="color: #666; font-size: 1.1rem;">Je hebt nog geen facturen.</p>
        </div>
    @else
        <div class="employer-activity-section" style="margin-top: 2rem;">
            <table>
                <thead>
                    <tr>
                        <th>Factuurnummer</th>
                        <th>Datum</th>
                        <th>Beschrijving</th>
                        <th>Bedrag</th>
                        <th>Status</th>
                        <th>Acties</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoices as $invoice)
                    <tr>
                        <td><strong>{{ $invoice->invoice_number }}</strong></td>
                        <td>{{ $invoice->issued_date->format('d-m-Y') }}</td>
                        <td>{{ $invoice->description }}</td>
                        <td>€{{ number_format($invoice->amount, 2, ',', '.') }}</td>
                        <td>
                            @php
                                $statusColors = match($invoice->status) {
                                    'paid' => ['bg' => 'rgba(4, 211, 0, 0.3)', 'text' => '#00BC0D'],
                                    'pending' => ['bg' => 'rgba(255, 132, 0, 0.3)', 'text' => '#FF8400'],
                                    'overdue' => ['bg' => 'rgba(255, 22, 22, 0.3)', 'text' => '#FF1616'],
                                    'cancelled' => ['bg' => 'rgba(229, 231, 235, 0.3)', 'text' => '#4B5563'],
                                    default => ['bg' => 'rgba(229, 231, 235, 0.3)', 'text' => '#4B5563']
                                };
                                
                                $statusLabels = [
                                    'paid' => 'Betaald',
                                    'pending' => 'In behandeling',
                                    'overdue' => 'Te laat',
                                    'cancelled' => 'Geannuleerd',
                                ];
                                
                                $displayStatus = $statusLabels[$invoice->status] ?? ucfirst($invoice->status);
                            @endphp
                            
                            <span class="employer-activity-badge" style="background-color: {{ $statusColors['bg'] }}; color: {{ $statusColors['text'] }};">
                                {{ $displayStatus }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('employer.invoices.show', $invoice->id) }}" 
                               class="btn-primary" 
                               style="padding: 0.5rem 1rem; font-size: 0.9rem; text-decoration: none; display: inline-block;">
                                Bekijken
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</section>
@endsection
