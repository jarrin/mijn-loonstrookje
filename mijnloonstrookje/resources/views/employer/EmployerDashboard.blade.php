@extends('layout.Layout')

@section('title', 'Werkgever Dashboard - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="employer-page-title">Werkgever Dashboard</h1>
    <p class="employer-welcome-text">Welkom {{ auth()->user()->name }}, beheer hier je medewerkers en loonstrookjes.</p>
    
    <div class="dashboard-tiles">
        <!-- Tile 1: Company Info with Date/Time -->
        <div class="dashboard-tile">
            <h3 class="tile-company-name">{{ $company ? $company->name : 'DMG' }}</h3>
            <div class="tile-time" id="current-time">12:38</div>
            <div class="tile-date" id="current-date">Dinsdag, 29-10</div>
        </div>

        <!-- Tile 2: Total Employees -->
        <div class="dashboard-tile">
            <h3 class="tile-title">Totaal aantal<br>werknemers</h3>
            <div class="tile-number">{{ $employeeCount }}</div>
            <div class="tile-subtitle">van de {{ $maxEmployees }}</div>
        </div>

        <!-- Tile 3: Subscription Plan -->
        <div class="dashboard-tile">
            <h3 class="tile-title">Mijn huidige<br>abonnement</h3>
            @php
                $subscriptionName = 'Geen abonnement';
                $subscriptionPrice = 0;
                
                if ($company && $company->subscription) {
                    $subscriptionName = ucfirst($company->subscription->subscription_plan);
                    $subscriptionPrice = $company->subscription->price;
                } elseif ($company && $company->customSubscription) {
                    $subscriptionName = $company->customSubscription->title;
                    $subscriptionPrice = $company->customSubscription->price;
                }
            @endphp
            <div class="tile-plan-name">{{ $subscriptionName }}</div>
            <div class="tile-price">€{{ number_format($subscriptionPrice, 2, ',', '.') }}</div>
        </div>

        <!-- Tile 4: Next Payment -->
        <div class="dashboard-tile">
            <h3 class="tile-title">Eerstvolgende<br>betaling</h3>
            @if($nextInvoice)
                <div class="tile-amount">€{{ number_format($nextInvoice->amount, 2, ',', '.') }}</div>
                <div class="tile-due-date">{{ $nextInvoice->due_date->format('j F Y') }}</div>
            @else
                <div class="tile-amount">€0,00</div>
                <div class="tile-due-date">Geen openstaande facturen</div>
            @endif
        </div>
    </div>
    
    <!-- Recent Activity Logs -->
    <div class="employer-activity-section">
        <h2 class="employer-activity-title">Recente Activiteit</h2>
        
        @include('components.TableFilterBar', [
            'filters' => [
                [
                    'label' => 'Type actie',
                    'options' => ['Login', 'Document uploaded', 'Document revised', 'Document deleted', 'Document restored', 'Employee created']
                ],
                [
                    'label' => 'Periode',
                    'options' => ['Vandaag', 'Deze week', 'Deze maand']
                ],
                [
                    'label' => 'Sorteer op',
                    'options' => ['Nieuwste eerst', 'Oudste eerst', 'Gebruiker']
                ]
            ]
        ])
        
        <table>
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>Gebruiker</th>
                    <th>Actie</th>
                    <th>Beschrijving</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentLogs as $log)
                <tr>
                    <td>{{ $log->created_at->format('d-m-Y H:i') }}</td>
                    <td>{{ $log->user ? $log->user->name : 'N/A' }}</td>
                    <td>
                        @php
                            $colors = match($log->action) {
                                'login', 'inloggen' => ['bg' => 'rgba(4, 211, 0, 0.3)', 'text' => '#00BC0D'],
                                'document_uploaded', 'document_geupload' => ['bg' => 'rgba(0, 149, 255, 0.3)', 'text' => '#0095FF'],
                                'document_revised', 'document_herzien' => ['bg' => 'rgba(255, 132, 0, 0.3)', 'text' => '#FF8400'],
                                'document_deleted', 'document_verwijderd' => ['bg' => 'rgba(255, 22, 22, 0.3)', 'text' => '#FF1616'],
                                'document_restored', 'document_hersteld' => ['bg' => 'rgba(145, 0, 236, 0.3)', 'text' => '#9100EC'],
                                'employee_created', 'medewerker_aangemaakt' => ['bg' => 'rgba(165, 243, 252, 0.3)', 'text' => '#0891B2'],
                                'admin_office_added', 'admin_bureau_toegevoegd' => ['bg' => 'rgba(251, 207, 232, 0.3)', 'text' => '#DB2777'],
                                'gebruiker_status_gewijzigd' => ['bg' => 'rgba(251, 191, 36, 0.3)', 'text' => '#F59E0B'],
                                'inactief_inlogpoging' => ['bg' => 'rgba(239, 68, 68, 0.3)', 'text' => '#DC2626'],
                                default => ['bg' => 'rgba(229, 231, 235, 0.3)', 'text' => '#4B5563']
                            };
                            
                            $actionTranslations = [
                                'login' => 'Inloggen',
                                'inloggen' => 'Inloggen',
                                'document_uploaded' => 'Document geüpload',
                                'document_geupload' => 'Document geüpload',
                                'document_revised' => 'Document herzien',
                                'document_herzien' => 'Document herzien',
                                'document_deleted' => 'Document verwijderd',
                                'document_verwijderd' => 'Document verwijderd',
                                'document_restored' => 'Document hersteld',
                                'document_hersteld' => 'Document hersteld',
                                'employee_created' => 'Medewerker aangemaakt',
                                'medewerker_aangemaakt' => 'Medewerker aangemaakt',
                                'admin_office_added' => 'Admin bureau toegevoegd',
                                'admin_bureau_toegevoegd' => 'Admin bureau toegevoegd',
                                'gebruiker_status_gewijzigd' => 'Gebruiker status gewijzigd',
                                'inactief_inlogpoging' => 'Inactief inlogpoging',
                            ];
                            
                            $displayAction = $actionTranslations[$log->action] ?? ucfirst(str_replace('_', ' ', $log->action));
                        @endphp
                        <span class="employer-activity-badge" style="background-color: {{ $colors['bg'] }}; color: {{ $colors['text'] }};">
                            {{ $displayAction }}
                        </span>
                    </td>
                    <td>{{ $log->description ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align: center;">Nog geen activiteit</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($recentLogs->hasPages())
        <div class="employer-pagination-container">
            {{ $recentLogs->links('vendor.pagination.custom') }}
        </div>
        @endif
    </div>
</section>

<script>
function updateDateTime() {
    const now = new Date();
    
    // Update time (HH:MM)
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    document.getElementById('current-time').textContent = `${hours}:${minutes}`;
    
    // Update date (Dayname, DD-MM)
    const days = ['Zondag', 'Maandag', 'Dinsdag', 'Woensdag', 'Donderdag', 'Vrijdag', 'Zaterdag'];
    const dayName = days[now.getDay()];
    const day = String(now.getDate()).padStart(2, '0');
    const month = String(now.getMonth() + 1).padStart(2, '0');
    document.getElementById('current-date').textContent = `${dayName}, ${day}-${month}`;
}

// Update immediately and then every second
updateDateTime();
setInterval(updateDateTime, 1000);
</script>
@endsection