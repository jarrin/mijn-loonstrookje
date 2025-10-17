<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mijn Loonstrookje')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans">
    <header>
        <nav class="flex justify-between items-center p-4">
            <a href="{{ route('home') }}">Mijn Loonstrookje</a>
            
            @auth
                <div>
                    <span>{{ auth()->user()->name }}</span>
                    <ul class="list-none">
                        <li class="inline-block"><a href="{{ route('profile.two-factor-authentication') }}">2FA Instellingen</a></li>
                        <li class="inline-block">
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-4 py-2 cursor-pointer">Uitloggen</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('login') }}">Inloggen</a>
            @endauth
        </nav>
    </header>

    <main class="p-8 max-w-6xl">
        @if(session('success'))
            <div>{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div>{{ session('error') }}</div>
        @endif

        @yield('content')
    </main>
</body>
</html>