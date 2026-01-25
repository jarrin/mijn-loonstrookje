@extends('layout.Layout')

@section('title', 'Administratiekantoor Dashboard - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="admin-dashboard-header">Administratiekantoor Dashboard</h1>
    <p class="admin-dashboard-intro">Welkom {{ auth()->user()->name }}, beheer hier meerdere werkgevers en hun administratie.</p>
    
    <div class="admin-companies-section">        
        @if($companies->isEmpty())
            <div class="admin-no-companies">
                <p>Je hebt nog geen toegang tot bedrijven.</p>
                <p class="subtext">Wacht totdat een werkgever je toegang geeft tot hun bedrijf.</p>
            </div>
        @else
            <div class="admin-companies-grid">
                @foreach($companies as $company)
                    <a href="{{ route('administration.company.show', $company->id) }}" class="admin-company-card" 
                       >
                        <div class="admin-company-card-content">
                            <div>
                                @if($company->logo_path)
                                    <img src="{{ asset('storage/' . $company->logo_path) }}" 
                                         alt="{{ $company->name }} logo" 
                                         class="admin-company-logo">
                                @else
                                    <div class="admin-company-logo-placeholder" 
                                         style="background-color: {{ $company->secondary_color ?? 'rgba(59, 130, 246, 0.6)' }};">
                                        <svg class="admin-company-logo-icon" style="color: {{ $company->primary_color ?? '#3B82F6' }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                @endif
                                
                                @if($company->subscription)
                                    <span class="admin-company-status {{ $company->subscription->status === 'active' ? 'active' : 'inactive' }}">
                                        {{ ucfirst($company->subscription->status) }}
                                    </span>
                                @endif
                            </div>
                            
                            <div class="admin-company-footer">
                                <h3 class="admin-company-name">{{ $company->name }}</h3>
                                
                                @if($company->kvk_number)
                                    <p class="admin-company-kvk">KVK:{{ $company->kvk_number }}</p>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection