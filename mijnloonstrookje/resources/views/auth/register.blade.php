<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registreren - Mijn Loonstrookje</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-semibold text-gray-900">Maak je account aan</h2>
                @if(session('subscription_id'))
                    <p class="mt-2 text-sm text-gray-600">Stap 0 van 3</p>
                @endif
            </div>

            @if(session('subscription_id'))
                <div class="w-full bg-gray-200 rounded-full h-1 mb-8">
                    <div class="bg-blue-600 h-1 rounded-full" style="width: 10%"></div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded text-sm text-red-600">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('subscription_id'))
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded text-sm text-blue-700">
                    Je hebt een abonnement gekozen. Maak eerst je account aan.
                </div>
            @endif
        
            <div class="bg-white border border-gray-200 rounded p-8">
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf
                    
                    @if(session('subscription_id'))
                        <input type="hidden" name="subscription_id" value="{{ session('subscription_id') }}">
                    @endif
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Naam</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            autofocus
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                        >
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
            
                    <div>
                        <label for="kvk_number" class="block text-sm font-medium text-gray-700 mb-2">KVK Nummer</label>
                        <input
                            type="text"
                            id="kvk_number"
                            name="kvk_number"
                            value="{{ old('kvk_number') }}"
                            required
                            maxlength="8"
                            pattern="[0-9]{8}"
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                        >
                        @error('kvk_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
            
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">E-mailadres</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                        >
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Wachtwoord</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            minlength="8"
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                        >
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Bevestig Wachtwoord</label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                            minlength="8"
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"
                        >
                    </div>
                    
                    <div class="pt-2">
                        <button type="submit" class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded font-medium">
                            Account Aanmaken
                        </button>
                    </div>
            </div>
            
            <div class="text-center mt-6">
                <p class="text-sm text-gray-600">
                    Heb je al een account? 
                    <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700">Log hier in</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
