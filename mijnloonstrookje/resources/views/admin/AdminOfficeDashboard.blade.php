@extends('layout.Layout')

@section('title', 'Administratiekantoor Dashboard - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="text-2xl font-bold mb-4">Administratiekantoor Dashboard</h1>
    <p class="text-gray-600 mb-6">Welkom {{ auth()->user()->name }}, beheer hier meerdere werkgevers en hun administratie.</p>
    
    <div class="mt-8">
        <h2 class="text-xl font-semibold mb-4">Mijn Bedrijven</h2>
        
        @if($companies->isEmpty())
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
                <p class="text-gray-700">Je hebt nog geen toegang tot bedrijven.</p>
                <p class="text-sm text-gray-500 mt-2">Wacht totdat een werkgever je toegang geeft tot hun bedrijf.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($companies as $company)
                    <div class="bg-white rounded-lg p-6 shadow-sm hover:shadow-md transition-shadow duration-200" 
                         style="border: 3px solid {{ $company->primary_color ?? '#3B82F6' }};">
                        <div class="flex flex-col items-center text-center">
                            @if($company->logo_path)
                                <img src="{{ asset('storage/' . $company->logo_path) }}" 
                                     alt="{{ $company->name }} logo" 
                                     class="w-20 h-20 object-contain mb-4">
                            @else
                                <div class="w-20 h-20 rounded-full flex items-center justify-center mb-4" 
                                     style="background-color: {{ $company->secondary_color ?? 'rgba(59, 130, 246, 0.6)' }};">
                                    <svg class="w-10 h-10" style="color: {{ $company->primary_color ?? '#3B82F6' }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            @endif
                            
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $company->name }}</h3>
                            
                            @if($company->kvk_number)
                                <p class="text-sm text-gray-500 mb-3">KVK: {{ $company->kvk_number }}</p>
                            @endif
                            
                            @if($company->subscription)
                                <span class="inline-block px-3 py-1 text-xs font-medium rounded-full mb-4
                                    {{ $company->subscription->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($company->subscription->status) }}
                                </span>
                            @endif
                            
                            <div class="mt-auto w-full space-y-2">
                                <a href="{{ route('administration.company.show', $company->id) }}" 
                                   class="block w-full text-white px-4 py-2 rounded hover:opacity-90 transition-colors text-sm font-medium"
                                   style="background-color: {{ $company->primary_color ?? '#3B82F6' }};">
                                    Bekijk Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection