@extends('layout.Layout')

@section('title', 'Werkgever Dashboard - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="employer-page-title">Werkgever Dashboard</h1>
    <p class="employer-welcome-text">Welkom {{ auth()->user()->name }}, beheer hier je medewerkers en loonstrookjes.</p>
    
    <div class="dashboard-tiles">
        <!-- Tile 1: Company Info with Date/Time -->
        <div class="dashboard-tile">
            <h3 class="tile-company-name">{{ auth()->user()->company->name ?? 'DMG' }}</h3>
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
            <div class="tile-plan-name">{{ ucfirst($company->subscription->subscription_plan ?? 'Basic') }}</div>
            <div class="tile-price">€{{ number_format($company->subscription->price ?? 0, 2, ',', '.') }}</div>
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
                                'login' => ['bg' => '#A7F3D0', 'text' => '#059669'],
                                'document_uploaded' => ['bg' => '#BFDBFE', 'text' => '#1D4ED8'],
                                'document_revised' => ['bg' => '#FDE68A', 'text' => '#D97706'],
                                'document_deleted' => ['bg' => '#FECACA', 'text' => '#DC2626'],
                                'document_restored' => ['bg' => '#DDD6FE', 'text' => '#7C3AED'],
                                'employee_created' => ['bg' => '#A5F3FC', 'text' => '#0891B2'],
                                'admin_office_added' => ['bg' => '#FBCFE8', 'text' => '#DB2777'],
                                default => ['bg' => '#E5E7EB', 'text' => '#4B5563']
                            };
                        @endphp
                        <span class="employer-activity-badge" style="background-color: {{ $colors['bg'] }}; color: {{ $colors['text'] }};">
                            {{ ucfirst(str_replace('_', ' ', $log->action)) }}
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