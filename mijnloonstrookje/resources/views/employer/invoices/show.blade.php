@extends('layout.Layout')

@section('title', 'Factuur Details - Mijn Loonstrookje')

@section('content')
<section>
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('employer.invoices') }}" style="color: #0095FF; text-decoration: none; font-size: 0.95rem;">
            ← Terug naar facturen
        </a>
    </div>
    
    <div style="background: white; border-radius: 12px; padding: 2.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <div style="border-bottom: 2px solid #f3f4f6; padding-bottom: 2rem; margin-bottom: 2rem;">
            <h1 style="font-size: 2rem; font-weight: 700; color: #1f2937; margin-bottom: 0.5rem;">
                Factuur {{ $invoice->invoice_number }}
            </h1>
            <p style="color: #6b7280; font-size: 1rem;">
                Uitgegeven op {{ $invoice->issued_date->format('d F Y') }}
            </p>
        </div>
        
        <!-- Factuur Status -->
        <div style="margin-bottom: 2.5rem;">
            <h3 style="font-size: 1rem; font-weight: 600; color: #4b5563; margin-bottom: 0.75rem;">Status</h3>
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
            
            <span class="employer-activity-badge" style="background-color: {{ $statusColors['bg'] }}; color: {{ $statusColors['text'] }}; font-size: 1rem; padding: 0.5rem 1rem;">
                {{ $displayStatus }}
            </span>
            
            @if($invoice->paid_at)
                <p style="color: #6b7280; font-size: 0.9rem; margin-top: 0.5rem;">
                    Betaald op {{ $invoice->paid_at->format('d F Y \o\m H:i') }}
                </p>
            @endif
        </div>
        
        <!-- Bedrijfsgegevens -->
        <div style="margin-bottom: 2.5rem;">
            <h3 style="font-size: 1rem; font-weight: 600; color: #4b5563; margin-bottom: 0.75rem;">Bedrijfsgegevens</h3>
            <div style="background: #f9fafb; border-radius: 8px; padding: 1.25rem;">
                <p style="margin: 0 0 0.5rem 0; color: #1f2937; font-weight: 600;">{{ $invoice->company->name }}</p>
                @if($invoice->company->kvk_number)
                    <p style="margin: 0; color: #6b7280; font-size: 0.9rem;">KVK: {{ $invoice->company->kvk_number }}</p>
                @endif
            </div>
        </div>
        
        <!-- Abonnement Details -->
        <div style="margin-bottom: 2.5rem;">
            <h3 style="font-size: 1rem; font-weight: 600; color: #4b5563; margin-bottom: 0.75rem;">Abonnement</h3>
            <div style="background: #f9fafb; border-radius: 8px; padding: 1.25rem;">
                @if($invoice->subscription)
                    <p style="margin: 0 0 0.5rem 0; color: #1f2937; font-weight: 600;">{{ ucfirst($invoice->subscription->subscription_plan) }}</p>
                    <p style="margin: 0; color: #6b7280; font-size: 0.9rem;">Max. {{ $invoice->subscription->max_employees }} medewerkers</p>
                @elseif($invoice->customSubscription)
                    <p style="margin: 0 0 0.5rem 0; color: #1f2937; font-weight: 600;">Custom Abonnement</p>
                    <p style="margin: 0; color: #6b7280; font-size: 0.9rem;">
                        €{{ number_format($invoice->customSubscription->price, 2, ',', '.') }} {{ $invoice->customSubscription->billing_period }}
                    </p>
                    <p style="margin: 0.25rem 0 0 0; color: #6b7280; font-size: 0.9rem;">
                        Max. {{ $invoice->customSubscription->max_users }} gebruikers
                    </p>
                @else
                    <p style="margin: 0; color: #6b7280;">Geen abonnement gekoppeld</p>
                @endif
            </div>
        </div>
        
        <!-- Factuur Details -->
        <div style="margin-bottom: 2.5rem;">
            <h3 style="font-size: 1rem; font-weight: 600; color: #4b5563; margin-bottom: 0.75rem;">Factuur Details</h3>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 2px solid #e5e7eb;">
                        <th style="text-align: left; padding: 0.75rem 0; color: #6b7280; font-weight: 600; font-size: 0.9rem;">Beschrijving</th>
                        <th style="text-align: right; padding: 0.75rem 0; color: #6b7280; font-weight: 600; font-size: 0.9rem;">Bedrag</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 1rem 0; color: #1f2937;">{{ $invoice->description }}</td>
                        <td style="padding: 1rem 0; text-align: right; color: #1f2937;">€{{ number_format($invoice->amount, 2, ',', '.') }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td style="padding: 1rem 0; color: #1f2937; font-weight: 700; font-size: 1.1rem;">Totaal</td>
                        <td style="padding: 1rem 0; text-align: right; color: #1f2937; font-weight: 700; font-size: 1.1rem;">
                            €{{ number_format($invoice->amount, 2, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        
        <!-- Payment Details -->
        @if($invoice->mollie_payment_id)
        <div style="margin-bottom: 2.5rem;">
            <h3 style="font-size: 1rem; font-weight: 600; color: #4b5563; margin-bottom: 0.75rem;">Betalingsgegevens</h3>
            <div style="background: #f9fafb; border-radius: 8px; padding: 1.25rem;">
                <p style="margin: 0; color: #6b7280; font-size: 0.9rem;">
                    <strong style="color: #1f2937;">Betalings ID:</strong> {{ $invoice->mollie_payment_id }}
                </p>
            </div>
        </div>
        @endif
        
        <!-- Action Buttons -->
        <div style="margin-top: 2.5rem; display: flex; gap: 1rem;">
            <a href="{{ route('employer.invoices') }}" 
               class="btn-primary" 
               style="padding: 0.75rem 1.5rem; text-decoration: none;">
                Terug naar overzicht
            </a>
            <!-- Future: Download PDF button -->
            <!-- <button class="btn-secondary" style="padding: 0.75rem 1.5rem;">Download PDF</button> -->
        </div>
    </div>
</section>
@endsection
