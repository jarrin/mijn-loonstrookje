@extends('layout.AuthLayout')

@section('title', 'Account Activeren - Mijn Loonstrookje')

@section('content')
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-800">Account Activeren</h2>
        <p class="mt-2 text-sm text-gray-600">Vul je gegevens in om je account te activeren</p>
    </div>
    
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif
    
    <form action="{{ route('invitation.register', $invitation->token) }}" method="POST" class="space-y-5">
        @csrf
        
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                E-mailadres
            </label>
            <input 
                type="email" 
                id="email" 
                value="{{ $invitation->email }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-600 cursor-not-allowed"
                disabled
            >
            <p class="text-xs text-gray-500 mt-1">Dit e-mailadres kan niet worden gewijzigd</p>
        </div>
        
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                Volledige Naam <span class="text-red-500">*</span>
            </label>
            <input 
                type="text" 
                id="name" 
                name="name" 
                value="{{ old('name') }}"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                required
                placeholder="Jan Jansen"
                autofocus
            >
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>
        
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
                Wachtwoord <span class="text-red-500">*</span>
            </label>
            <input 
                type="password" 
                id="password" 
                name="password" 
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('password') border-red-500 @enderror"
                required
                minlength="8"
            >
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-500 mt-1">Minimaal 8 tekens</p>
        </div>
        
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                Bevestig Wachtwoord <span class="text-red-500">*</span>
            </label>
            <input 
                type="password" 
                id="password_confirmation" 
                name="password_confirmation" 
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                required
                minlength="8"
            >
        </div>
        
        <div class="pt-2">
            <button 
                type="submit" 
                class="w-full bg-blue-600 text-white px-6 py-3 rounded-md hover:bg-blue-700 transition-colors font-semibold focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                Account Activeren
            </button>
        </div>
    </form>
    
    {{-- <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
        <p class="text-xs text-blue-800">
            <strong>ℹ️ Volgende stap:</strong> Na het activeren van je account word je gevraagd om twee-factor authenticatie in te stellen voor extra beveiliging.
        </p>
    </div> --}}
@endsection
