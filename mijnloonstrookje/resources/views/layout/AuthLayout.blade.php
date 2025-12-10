<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mijn Loonstrookje')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-blue-600">Mijn Loonstrookje</h1>
                <p class="mt-2 text-sm text-gray-600">Uw digitale loonstrookje beheer</p>
            </div>

            <!-- Content Card -->
            <div class="bg-white shadow-lg rounded-lg px-8 py-10">
                @yield('content')
            </div>

            <!-- Footer -->
            <div class="mt-6 text-center text-sm text-gray-600">
                <p>&copy; {{ date('Y') }} Mijn Loonstrookje. Alle rechten voorbehouden.</p>
            </div>
        </div>
    </div>
</body>
</html>
