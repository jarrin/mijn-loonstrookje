@extends('layout.Layout')

@section('title', $company->name . ' - Details - Mijn Loonstrookje')

@section('content')
<section>
    <div class="mb-6">
        <a href="{{ route('administration.dashboard') }}" class="hover:underline mb-4 inline-block" style="color: var(--primary-color);">
            ← Terug naar Dashboard
        </a>
        
        <h1 class="text-2xl font-bold mb-2">{{ $company->name }}</h1>
        @if($company->kvk_number)
            <p class="text-gray-600">KVK: {{ $company->kvk_number }}</p>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white border rounded p-4">
            <h3 class="font-semibold mb-2">Medewerkers</h3>
            <p class="text-2xl font-bold mb-2">{{ $employeesCount }}</p>
            <a href="{{ route('administration.company.employees', $company->id) }}" class="hover:underline text-sm" style="color: var(--primary-color);">
                Bekijk medewerkers
            </a>
        </div>

        <div class="bg-white border rounded p-4">
            <h3 class="font-semibold mb-2">Documenten</h3>
            <p class="text-2xl font-bold mb-2">{{ $documentsCount }}</p>
            <a href="{{ route('administration.company.documents', $company->id) }}" class="hover:underline text-sm" style="color: var(--primary-color);">
                Bekijk documenten
            </a>
        </div>

        <div class="bg-white border rounded p-4">
            <h3 class="font-semibold mb-2">Abonnement</h3>
            @if($company->subscription)
                <p class="font-bold mb-1">{{ ucfirst($company->subscription->plan) }}</p>
                <span class="text-xs px-2 py-1 rounded {{ $company->subscription->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100' }}">
                    {{ ucfirst($company->subscription->status) }}
                </span>
                @if($company->subscription->expires_at)
                    <p class="text-sm text-gray-600 mt-2">
                        Vervalt: {{ \Carbon\Carbon::parse($company->subscription->expires_at)->format('d-m-Y') }}
                    </p>
                @endif
            @else
                <p class="text-gray-600">Geen actief abonnement</p>
            @endif
        </div>
    </div>

    <div class="bg-white border rounded p-4">
        <h2 class="text-xl font-semibold mb-4">Bedrijfsinformatie</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Bedrijfsnaam</label>
                <p>{{ $company->name }}</p>
            </div>
            @if($company->kvk_number)
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">KVK Nummer</label>
                    <p>{{ $company->kvk_number }}</p>
                </div>
            @endif
            @if($company->address)
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Adres</label>
                    <p>{{ $company->address }}</p>
                </div>
            @endif
            @if($company->city)
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Plaats</label>
                    <p>{{ $company->city }}</p>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
