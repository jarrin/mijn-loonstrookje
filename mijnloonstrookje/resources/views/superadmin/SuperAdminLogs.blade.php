@extends('layout.Layout')

@section('title', 'Logs - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="superadmin-page-title">Systeem Logs</h1>
    
    <!-- Filters -->
    <form method="GET" class="superadmin-filter-form">
        <div class="superadmin-filter-grid">
            <div>
                <label class="superadmin-filter-label">Actie</label>
                <select name="action" class="superadmin-filter-select">
                    <option value="">Alle acties</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $action)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="superadmin-filter-label">Bedrijf</label>
                <select name="company_id" class="superadmin-filter-select">
                    <option value="">Alle bedrijven</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="superadmin-filter-label">Van Datum</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="superadmin-filter-input">
            </div>
            <div>
                <label class="superadmin-filter-label">Tot Datum</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="superadmin-filter-input">
            </div>
        </div>
        <div class="superadmin-filter-actions">
            <button type="submit" class="superadmin-button-primary">Filter</button>
            <a href="{{ route('superadmin.logs') }}" class="superadmin-button-secondary">Reset</a>
        </div>
    </form>
    
    <table>
        <thead>
            <tr>
                <th>Timestamp</th>
                <th>Gebruiker</th>
                <th>Bedrijf</th>
                <th>Actie</th>
                <th>Beschrijving</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
            <tr>
                <td>{{ $log->created_at->format('d-m-Y H:i:s') }}</td>
                <td>{{ $log->user ? $log->user->name : 'N/A' }}</td>
                <td>
                    @if($log->company)
                        {{ $log->company->name }}
                    @elseif($log->user && $log->user->role === 'administration_office')
                        @php
                            $companies = $log->user->companies;
                            $count = $companies->count();
                            $allCompanies = $companies->pluck('name')->join(', ');
                        @endphp
                        @if($count === 1)
                            {{ $companies->first()->name }}
                        @elseif($count > 1)
                            <span title="{{ $allCompanies }}" style="cursor: help; border-bottom: 1px dotted #666;">
                                {{ $count }} Bedrijven
                            </span>
                        @else
                            N/A
                        @endif
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    @php
                        $badgeClass = match($log->action) {
                            'login' => 'superadmin-log-badge-login',
                            'document_uploaded' => 'superadmin-log-badge-upload',
                            'document_revised' => 'superadmin-log-badge-revised',
                            'document_deleted' => 'superadmin-log-badge-deleted',
                            'document_restored' => 'superadmin-log-badge-restored',
                            'employee_created' => 'superadmin-log-badge-created',
                            'admin_office_added' => 'superadmin-log-badge-added',
                            default => 'superadmin-log-badge-default'
                        };
                    @endphp
                    <span class="superadmin-log-badge {{ $badgeClass }}">
                        {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                    </span>
                </td>
                <td>{{ $log->description ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">Geen logs gevonden</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <!-- Pagination -->
    <div class="superadmin-pagination-wrapper">
        {{ $logs->links() }}
    </div>
    
    <div class="superadmin-actions-container">
        <a href="{{ route('superadmin.dashboard') }}" class="superadmin-button-secondary">Terug naar Dashboard</a>
    </div>
</section>
@endsection
