@extends('layout.Layout')

@section('title', $company->name . ' - Details - Mijn Loonstrookje')

@section('content')
<section>
    <div class="company-details-header">
        <a href="{{ route('administration.dashboard') }}" class="company-back-link" style="color: var(--primary-color);">
            ← Terug naar Dashboard
        </a>
        
        <h1 class="company-title">{{ $company->name }}</h1>
        @if($company->kvk_number)
            <p class="company-kvk-text">KVK: {{ $company->kvk_number }}</p>
        @endif
    </div>

    <div class="company-stats-grid">
        <div class="company-stat-card">
            <h3 class="company-stat-title">Medewerkers</h3>
            <p class="company-stat-value">{{ $employeesCount }}</p>
            <a href="{{ route('administration.company.employees', $company->id) }}" class="company-stat-link" style="color: var(--primary-color);">
                Bekijk medewerkers
            </a>
        </div>

        <div class="company-stat-card">
            <h3 class="company-stat-title">Documenten</h3>
            <p class="company-stat-value">{{ $documentsCount }}</p>
            <a href="{{ route('administration.company.documents', $company->id) }}" class="company-stat-link" style="color: var(--primary-color);">
                Bekijk documenten
            </a>
        </div>

        <div class="company-stat-card">
            <h3 class="company-stat-title">Abonnement</h3>
            @if($company->subscription)
                <p class="company-subscription-plan">{{ ucfirst($company->subscription->plan) }}</p>
                <span class="company-subscription-status {{ $company->subscription->status === 'active' ? 'active' : 'inactive' }}">
                    {{ ucfirst($company->subscription->status) }}
                </span>
                @if($company->subscription->expires_at)
                    <p class="company-subscription-expires">
                        Vervalt: {{ \Carbon\Carbon::parse($company->subscription->expires_at)->format('d-m-Y') }}
                    </p>
                @endif
            @else
                <p class="company-subscription-none">Geen actief abonnement</p>
            @endif
        </div>
    </div>

    <div class="company-info-card">
        <h2 class="company-info-title">Bedrijfsinformatie</h2>
        <div class="company-info-grid">
            <div>
                <label class="company-info-field-label">Bedrijfsnaam</label>
                <p>{{ $company->name }}</p>
            </div>
            @if($company->kvk_number)
                <div>
                    <label class="company-info-field-label">KVK Nummer</label>
                    <p>{{ $company->kvk_number }}</p>
                </div>
            @endif
            @if($company->address)
                <div>
                    <label class="company-info-field-label">Adres</label>
                    <p>{{ $company->address }}</p>
                </div>
            @endif
            @if($company->city)
                <div>
                    <label class="company-info-field-label">Plaats</label>
                    <p>{{ $company->city }}</p>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
