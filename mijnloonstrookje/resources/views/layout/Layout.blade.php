<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mijn Loonstrookje')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <nav class="main-side-nav">
        <div class="logo">
            <h1>LOGO</h1>
        </div>
        <div class="buttons">
            <div class="nav-tabs">
                @auth
                    {{-- SuperAdmin links --}}
                    @if(auth()->user()->hasRole('super_admin'))
                        <a href="{{ route('superadmin.dashboard') }}">Home</a>
                        <a href="{{ route('superadmin.subscriptions') }}">Subscriptions</a>
                        <a href="{{ route('superadmin.logs') }}">Logs</a>
                        <a href="{{ route('superadmin.facturation') }}">Facturatie</a>
                    @endif

                    {{-- Administratiekantoor links --}}
                    @if(auth()->user()->hasRole('administration_office'))
                        <a href="{{ route('administration.dashboard') }}">Home</a>
                        <a href="{{ route('administration.employees') }}">Werknemers</a>
                        <a href="{{ route('administration.documents') }}">Documenten</a>
                    @endif

                    {{-- Werkgever links --}}
                    @if(auth()->user()->hasRole('employer'))
                        <a href="{{ route('employer.dashboard') }}">Dashboard</a>
                        <a href="{{ route('employer.employees') }}">Werknemers</a>
                        <a href="{{ route('employer.admin-offices') }}">Administratiebureau's</a>
                    @endif
                    
                    {{-- Medewerker links --}}
                    @if(auth()->user()->hasRole('employee'))
                        <a href="{{ route('employee.dashboard') }}">Dashboard</a>
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

    <main class="main-content">
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>