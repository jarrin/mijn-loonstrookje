@extends('layout.Layout')

@section('title', 'Werkgever Dashboard - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="employer-page-title">Werkgever Dashboard</h1>
    <p class="employer-welcome-text">Welkom {{ auth()->user()->name }}, beheer hier je medewerkers en loonstrookjes.</p>
    
    <div class="dashboard-tiles">
        <!-- Tile 1: Company Info with Date/Time -->
        <div class="dashboard-tile">
            <div class="tile-top">
                <div class="tile-header">
                    <h3 class="tile-company-name">{{ $company ? $company->name : 'DMG' }}</h3>
                    <div class="tile-icon" style="background-color: rgba(139, 92, 246, 0.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#8B5CF6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock-icon lucide-clock"><path d="M12 6v6l4 2"/><circle cx="12" cy="12" r="10"/></svg>
                    </div>
                </div>
            </div>
            <div class="tile-bottom">
                <div class="tile-time" id="current-time">12:38</div>
                <div class="tile-date" id="current-date">Dinsdag, 29-10</div>
            </div>
        </div>

        <!-- Tile 2: Total Employees -->
        <div class="dashboard-tile">
            <div class="tile-top">
                <div class="tile-header">
                    <h3 class="tile-title">Totaal aantal<br>werknemers</h3>
                    <div class="tile-icon" style="background-color: rgba(59, 130, 246, 0.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-icon lucide-users"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><path d="M16 3.128a4 4 0 0 1 0 7.744"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><circle cx="9" cy="7" r="4"/></svg>
                    </div>
                </div>
            </div>
            <div class="tile-bottom">
                <div class="tile-number">{{ $employeeCount }}</div>
                <div class="tile-subtitle">van de {{ $maxEmployees }}</div>
            </div>
        </div>

        <!-- Tile 3: Subscription Plan -->
        <div class="dashboard-tile">
            <div class="tile-top">
                <div class="tile-header">
                    <h3 class="tile-title">Mijn huidige<br>abonnement</h3>
                    <div class="tile-icon" style="background-color: rgba(34, 197, 94, 0.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#22C55E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-package-icon lucide-package"><path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z"/><path d="M12 22V12"/><polyline points="3.29 7 12 12 20.71 7"/><path d="m7.5 4.27 9 5.15"/></svg>
                    </div>
                </div>
            </div>
            <div class="tile-bottom">
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
        </div>

        <!-- Tile 4: Next Payment -->
        <div class="dashboard-tile">
            <div class="tile-top">
                <div class="tile-header">
                    <h3 class="tile-title">Eerstvolgende<br>betaling</h3>
                    <div class="tile-icon" style="background-color: rgba(251, 191, 36, 0.1);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#FBBF24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-calendar-days-icon lucide-calendar-days"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/><path d="M8 14h.01"/><path d="M12 14h.01"/><path d="M16 14h.01"/><path d="M8 18h.01"/><path d="M12 18h.01"/><path d="M16 18h.01"/></svg>
                    </div>
                </div>
            </div>
            <div class="tile-bottom">
                @if($nextInvoice)
                    <div class="tile-amount">€{{ number_format($nextInvoice->amount, 2, ',', '.') }}</div>
                    <div class="tile-due-date">{{ $nextInvoice->due_date->format('j F Y') }}</div>
                @else
                    <div class="tile-amount">€0,00</div>
                    <div class="tile-due-date">Geen openstaande facturen</div>
                @endif
            </div>
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