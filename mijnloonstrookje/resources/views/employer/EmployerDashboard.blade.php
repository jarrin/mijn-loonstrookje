@extends('layout.Layout')

@section('title', 'Werkgever Dashboard - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="text-2xl mb-4">Werkgever Dashboard</h1>
    <p>Welkom {{ auth()->user()->name }}, beheer hier je medewerkers en loonstrookjes.</p>
    
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
    <div class="mt-8">
        <h2 class="text-xl font-semibold mb-4">Recente Activiteit</h2>
        <table>
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>Gebruiker</th>
                    <th>Actie</th>
                    <th>Beschrijving</th>
                    <th>IP Adres</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentLogs as $log)
                <tr>
                    <td>{{ $log->created_at->format('d-m-Y H:i') }}</td>
                    <td>{{ $log->user ? $log->user->name : 'N/A' }}</td>
                    <td>
                        <span class="px-2 py-1 rounded text-xs" style="background-color: {{ 
                            match($log->action) {
                                'login' => '#10B981',
                                'document_uploaded' => '#3B82F6',
                                'document_revised' => '#F59E0B',
                                'document_deleted' => '#EF4444',
                                'document_restored' => '#8B5CF6',
                                'employee_created' => '#06B6D4',
                                'admin_office_added' => '#EC4899',
                                default => '#6B7280'
                            }
                        }}; color: white;">
                            {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                        </span>
                    </td>
                    <td>{{ $log->description ?? '-' }}</td>
                    <td>{{ $log->ip_address ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Nog geen activiteit</td>
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

<style>
.dashboard-tiles {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 24px;
    margin-top: 32px;
}

.dashboard-tile {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    transition: transform 0.2s, box-shadow 0.2s;
}

.dashboard-tile:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
}

.tile-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 20px;
}

.tile-company-name {
    font-family: var(--font-body-bold);
    font-size: 18px;
    color: #6B7280;
    margin: 0 0 24px 0;
    font-weight: 600;
}

.tile-title {
    font-family: var(--font-body-bold);
    font-size: 18px;
    color: #6B7280;
    margin: 0 0 24px 0;
    font-weight: 600;
    line-height: 1.3;
}

.tile-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
}

.tile-time {
    font-family: var(--font-heading);
    font-size: 48px;
    font-weight: bold;
    color: #111827;
    line-height: 1;
    margin-bottom: 12px;
}

.tile-date {
    font-family: var(--font-body);
    font-size: 16px;
    color: #6B7280;
}

.tile-number {
    font-family: var(--font-heading);
    font-size: 48px;
    font-weight: bold;
    color: #111827;
    line-height: 1;
    margin-bottom: 12px;
}

.tile-subtitle {
    font-family: var(--font-body);
    font-size: 16px;
    color: #6B7280;
}

.tile-plan-name {
    font-family: var(--font-heading);
    font-size: 48px;
    font-weight: bold;
    color: #111827;
    line-height: 1;
    margin-bottom: 12px;
}

.tile-price {
    font-family: var(--font-body);
    font-size: 16px;
    color: #6B7280;
}

.tile-amount {
    font-family: var(--font-heading);
    font-size: 48px;
    font-weight: bold;
    color: #111827;
    line-height: 1;
    margin-bottom: 12px;
}

.tile-due-date {
    font-family: var(--font-body);
    font-size: 16px;
    color: #6B7280;
}
</style>
@endsection