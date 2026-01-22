<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-mail Geverifieerd - Mijn Loonstrookje</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-sky-50 min-h-screen">
    <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">
            <!-- Main Card -->
            <div class="bg-white rounded-2xl shadow-sm p-8 text-center">
                <!-- Success Icon -->
                <div class="flex justify-center mb-6">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                        <svg class="w-9 h-9 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>

                <h2 class="text-2xl font-bold text-gray-900 mb-2">E-mail geverifieerd!</h2>
                <p class="text-gray-600 mb-8">
                    Je e-mailadres is succesvol geverifieerd. Je kunt dit venster nu sluiten en teruggaan naar het registratieproces.
                </p>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <p class="text-sm text-blue-800">
                        💡 <strong>Tip:</strong> Ga terug naar het vorige tabblad om verder te gaan met de registratie.
                    </p>
                </div>

                <button onclick="window.close()" 
                        class="w-full py-3 px-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-lg transition-colors">
                    Sluit dit venster
                </button>
            </div>

            <p class="text-center text-sm text-gray-500 mt-6">
                Mijn Loonstrookje © {{ date('Y') }}
            </p>
        </div>
    </div>
</body>
</html>
