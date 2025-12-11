<nav class="main-side-nav">
    <div class="buttons">
        <div class="nav-tabs">
            @auth
                {{-- Links for all authenticated users --}}
                <a href="{{ route('home') }}">Home</a>
                
                {{-- Admin and SuperAdmin links --}}
                @if(auth()->user()->hasRole(['admin', 'superadmin']))
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                @endif
                
                {{-- SuperAdmin only links --}}
                @if(auth()->user()->hasRole('superadmin'))
                    <a href="{{ route('superadmin.subscriptions') }}">Subscriptions</a>
                    <a href="{{ route('superadmin.logs') }}">Logs</a>
                    <a href="{{ route('superadmin.facturation') }}">Facturatie</a>
                @endif
                
                {{-- Regular user links --}}
                @if(auth()->user()->hasRole('user'))
                    <a href="{{ route('payslips.index') }}">Mijn Loonstrookjes</a>
                @endif
            @endauth
        </div>
        
        <div class="button-nav">
            @auth
                <div class="user-menu">
                    <span>{{ auth()->user()->name }}</span>
                    <ul>
                        <li><a href="{{ route('profile.two-factor-authentication') }}">2FA Instellingen</a></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="logout-button">Uitloggen</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('login') }}">Inloggen</a>
            @endauth
        </div>
    </div>
</nav>







<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mijn Loonstrookje')</title>
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans">
    <header>
        <nav class="main-side-nav">
            <div class="logo">
                <h1>LOGO</h1>
            </div>
            <div class="buttons">
                <div class="nav-tabs">
                    <a href="{{ route('superadmin.dashboard') }}">Home</a>
                    <a href="{{ route('superadmin.subscriptions') }}">Subscriptions</a>
                    <a href="{{ route('superadmin.logs') }}">Logs</a>
                    <a href="{{ route('superadmin.facturation') }}">Facturatie</a>
                </div>
                <div class="button-nav">
                    @auth
                        <div>
                            <span>{{ auth()->user()->name }}</span>
                            <ul>
                                <li><a href="{{ route('profile.two-factor-authentication') }}">2FA Instellingen</a></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="logout-button">Uitloggen</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}">Inloggen</a>
                    @endauth
                </div>
            </div>
        </nav>
    </header>

    <div class="content">
        <a href="{{ route('home') }}">Mijn Loonstrookje</a>


        <main>
            @if(session('success'))
                <div>{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div>{{ session('error') }}</div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>