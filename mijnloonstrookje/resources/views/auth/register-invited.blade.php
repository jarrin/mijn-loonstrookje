@extends('layout.Layout')

@section('title', 'Account Activeren - Mijn Loonstrookje')

@section('content')
<section>
    <div class="max-w-md mx-auto">
        <h1 class="text-2xl mb-4 text-center">Welkom bij Mijn Loonstrookje!</h1>
        
        {{-- <div class="bg-blue-50 border border-blue-200 rounded p-4 mb-6">
            <p class="text-blue-800">
                Je bent uitgenodigd om deel uit te maken van het platform. Vul je gegevens in om je account te activeren.
            </p>
        </div> --}}
        
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif
        
        <form action="{{ route('invitation.register', $invitation->token) }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold mb-2">
                    E-mailadres
                </label>
                <input 
                    type="email" 
                    id="email" 
                    value="{{ $invitation->email }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded bg-gray-100"
                    disabled
                >
                <p class="text-sm text-gray-600 mt-1">Dit e-mailadres kan niet worden gewijzigd.</p>
            </div>
            
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-bold mb-2">
                    Volledige Naam *
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                    required
                    placeholder="Jan Jansen"
                >
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-bold mb-2">
                    Wachtwoord *
                </label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500 @error('password') border-red-500 @enderror"
                    required
                    minlength="8"
                >
                @error('password')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-sm text-gray-600 mt-1">Minimaal 8 tekens</p>
            </div>
            
            <div class="mb-6">
                <label for="password_confirmation" class="block text-gray-700 font-bold mb-2">
                    Bevestig Wachtwoord *
                </label>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                    minlength="8"
                >
            </div>
            
            <button 
                type="submit" 
                class="w-full bg-blue-500 text-white px-6 py-3 rounded hover:bg-blue-600 font-bold"
            >
                Account Activeren
            </button>
        </form>
        
        {{-- <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded">
            <h3 class="font-bold text-yellow-800 mb-2">⚠️ Volgende Stap</h3>
            <p class="text-sm text-yellow-700">
                Na het activeren van je account word je gevraagd om twee-factor authenticatie in te stellen voor extra beveiliging.
            </p>
        </div> --}}
    </div>
</section>
@endsection
