<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mijn Loonstrookje')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="empty-layout">
    <main class="main-content">
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>