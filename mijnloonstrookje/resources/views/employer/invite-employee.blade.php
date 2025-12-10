@extends('layout.Layout')

@section('title', 'Medewerker Uitnodigen - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="text-2xl mb-4">Medewerker Uitnodigen</h1>
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif
    
    <div class="max-w-md">
        <p class="mb-4 text-gray-600">
            Voer het e-mailadres van de werknemer in. Deze ontvangt een uitnodiging om zijn/haar account aan te maken.
        </p>
        
        <form action="{{ route('employer.send.invitation') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold mb-2">
                    E-mailadres *
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 @error('email') border-red-500 @enderror"
                    required
                    placeholder="werknemer@example.com"
                >
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex space-x-4">
                <button 
                    type="submit" 
                    class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600"
                >
                    Uitnodiging Versturen
                </button>
                
                <a 
                    href="{{ route('employer.employees') }}" 
                    class="bg-gray-500 text-white px-6 py-2 rounded hover:bg-gray-600"
                >
                    Annuleren
                </a>
            </div>
        </form>
        
        {{-- <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded">
            <h3 class="font-bold text-blue-800 mb-2">ℹ️ Wat gebeurt er nu?</h3>
            <ol class="list-decimal list-inside text-sm text-blue-700 space-y-1">
                <li>De werknemer ontvangt een e-mail met een activatielink</li>
                <li>Via deze link kan de werknemer zijn/haar gegevens invoeren</li>
                <li>Na registratie wordt twee-factor authenticatie verplicht ingesteld</li>
                <li>De werknemer kan vervolgens inloggen op het platform</li>
            </ol>
            <p class="text-sm text-blue-700 mt-2">
                <strong>Let op:</strong> De uitnodiging is 7 dagen geldig.
            </p>
        </div> --}}
    </div>
</section>
@endsection
