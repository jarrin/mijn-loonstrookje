<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Betaling Geslaagd - Mijn Loonstrookje</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4">
        <div class="max-w-md w-full">
            <div class="text-center mb-8">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                    <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-semibold text-gray-900">Betaling geslaagd!</h2>
                <p class="mt-2 text-sm text-gray-600">Je abonnement is geactiveerd</p>
            </div>

            <div class="w-full bg-gray-200 rounded-full h-1 mb-8">
                <div class="bg-green-600 h-1 rounded-full transition-all" style="width: 100%"></div>
            </div>

            <div class="bg-white border border-gray-200 rounded p-8">
                <div class="text-center">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Welkom bij Mijn Loonstrookje!</h3>
                        <p class="text-sm text-gray-600">
                            Je account is succesvol aangemaakt en je abonnement is actief.
                        </p>
                    </div>

                    @if($subscription)
                        <div class="bg-blue-50 border border-blue-200 rounded p-4 mb-6 text-left">
                            <h4 class="font-medium text-blue-900 mb-2">Jouw abonnement</h4>
                            <div class="space-y-1 text-sm text-blue-800">
                                <p><strong>Plan:</strong> {{ $subscription->name }}</p>
                                <p><strong>Prijs:</strong> €{{ number_format($subscription->price, 2, ',', '.') }} per maand</p>
                                <p><strong>Medewerkers:</strong> Tot {{ $subscription->max_employees }} medewerkers</p>
                            </div>
                        </div>
                    @endif

                    <div class="space-y-3">
                        <a href="{{ route('employer.dashboard') }}" 
                           class="block w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded font-medium text-center">
                            Ga naar Dashboard
                        </a>
                        
                        <div class="text-xs text-gray-500">
                            <p>Je ontvangt een bevestigingsmail op {{ auth()->user()->email }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 text-center text-sm text-gray-600">
                <p>Vragen? Neem contact op via support@mijnloonstrookje.nl</p>
            </div>
        </div>
    </div>
</body>
</html>
