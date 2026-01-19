<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mijn Loonstrookje')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary-color: #3B82F6;
            --secondary-color: rgba(59, 130, 246, 0.6);
            --light-bg-color: rgba(59, 130, 246, 0.15);
        }
    </style>
</head>
<body class="empty-layout">
    <main class="main-content">
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>