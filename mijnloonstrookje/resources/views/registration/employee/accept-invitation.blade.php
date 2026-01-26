<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uitnodiging Accepteren - Mijn Loonstrookje</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-sky-50 min-h-screen">
    <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-xl">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Uitnodiging Accepteren</h1>
                <p class="mt-2 text-gray-500">Log in om de uitnodiging te accepteren</p>
            </div>

            <!-- Main Card -->
            <div class="bg-white rounded-2xl shadow-sm p-8">
                <!-- Header Icon -->
                <div class="flex justify-center mb-4">
                    <div class="w-14 h-14 bg-blue-50 rounded-full flex items-center justify-center">
                        <svg class="w-7 h-7 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>

                <h2 class="text-xl font-bold text-gray-900 text-center mb-1">Welkom terug</h2>
                <p class="text-gray-500 text-center mb-6">Log in om toegang te krijgen tot {{ $invitation->company->name ?? 'het bedrijf' }}</p>

                <x-registration.status-messages />

                <!-- Info message -->
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-blue-600">Je bent uitgenodigd om toegang te krijgen. Log in met je bestaande account om de uitnodiging te accepteren.</p>
                </div>

                <form action="{{ route('invitation.login.accept', $invitation->token) }}" method="POST" class="space-y-5">
                    @csrf

                    <!-- E-mailadres -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">E-mailadres</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <input 
                                type="email" 
                                id="email" 
                                name="email" 
                                value="{{ old('email', $invitation->email) }}"
                                required
                                autofocus
                                placeholder="jouw@email.com"
                                class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Wachtwoord -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Wachtwoord</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input 
                                type="password" 
                                id="password" 
                                name="password" 
                                required
                                placeholder="Je wachtwoord"
                                class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
                        </div>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="w-full py-3 px-4 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors">
                        Inloggen en uitnodiging accepteren
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-xs text-gray-500">
                        Deze uitnodiging is geldig tot {{ $invitation->expires_at->format('d-m-Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
