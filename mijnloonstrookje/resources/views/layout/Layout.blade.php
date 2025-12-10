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
            <img src="{{ asset('images/loonstrookje-breed-logo-upscale-transparent.png') }}" alt="Mijn Loonstrookje">
        </div>
        <div class="buttons">
            <div class="nav-tabs">
                @auth
                    {{-- SuperAdmin links --}}
                    @if(auth()->user()->hasRole('super_admin'))
                        <a href="{{ route('superadmin.dashboard') }}" class="{{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
                            {!! file_get_contents(resource_path('assets/icons/home.svg')) !!}
                            <span>Home</span>
                        </a>
                        <a href="{{ route('superadmin.subscriptions') }}" class="{{ request()->routeIs('superadmin.subscriptions') ? 'active' : '' }}">
                            {!! file_get_contents(resource_path('assets/icons/credit-card.svg')) !!}
                            <span>Abonnementen</span>
                        </a>
                        <a href="{{ route('superadmin.logs') }}" class="{{ request()->routeIs('superadmin.logs') ? 'active' : '' }}">
                            {!! file_get_contents(resource_path('assets/icons/file-text.svg')) !!}
                            <span>Logs</span>
                        </a>
                        <a href="{{ route('superadmin.facturation') }}" class="{{ request()->routeIs('superadmin.facturation') ? 'active' : '' }}">
                            {!! file_get_contents(resource_path('assets/icons/invoice.svg')) !!}
                            <span>Facturatie</span>
                        </a>
                    @endif

                    {{-- Administratiekantoor links --}}
                    @if(auth()->user()->hasRole('administration_office'))
                        <a href="{{ route('administration.dashboard') }}" class="{{ request()->routeIs('administration.dashboard') ? 'active' : '' }}">
                            {!! file_get_contents(resource_path('assets/icons/home.svg')) !!}
                            <span>Home</span>
                        </a>
                        <a href="{{ route('administration.employees') }}" class="{{ request()->routeIs('administration.employees') ? 'active' : '' }}">
                            {!! file_get_contents(resource_path('assets/icons/users.svg')) !!}
                            <span>Werknemers</span>
                        </a>
                        <a href="{{ route('administration.documents') }}" class="{{ request()->routeIs('administration.documents') ? 'active' : '' }}">
                            {!! file_get_contents(resource_path('assets/icons/documents.svg')) !!}
                            <span>Documenten</span>
                        </a>
                    @endif

                    {{-- Werkgever links --}}
                    @if(auth()->user()->hasRole('employer'))
                        <a href="{{ route('employer.dashboard') }}" class="{{ request()->routeIs('employer.dashboard') ? 'active' : '' }}">
                            {!! file_get_contents(resource_path('assets/icons/home.svg')) !!}
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('employer.employees') }}" class="{{ request()->routeIs('employer.employees') ? 'active' : '' }}">
                            {!! file_get_contents(resource_path('assets/icons/users.svg')) !!}
                            <span>Werknemers</span>
                        </a>
                        <a href="{{ route('employer.admin-offices') }}" class="{{ request()->routeIs('employer.admin-offices') ? 'active' : '' }}">
                            {!! file_get_contents(resource_path('assets/icons/briefcase.svg')) !!}
                            <span>Administratiebureau's</span>
                        </a>
                    @endif
                    
                    {{-- Medewerker links --}}
                    @if(auth()->user()->hasRole('employee'))
                        <a href="{{ route('employee.dashboard') }}" class="{{ request()->routeIs('employee.dashboard') ? 'active' : '' }}">
                            {!! file_get_contents(resource_path('assets/icons/home.svg')) !!}
                            <span>Dashboard</span>
                        </a>
                    @endif
                @endauth
            </div>
            
            <div class="button-nav">
                @auth
                    <div class="user-menu-container">
                        <ul class="user-menu-dropdown" id="userMenuDropdown">
                            <li><a href="{{ route('profile.two-factor-authentication') }}">2FA Instellingen</a></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="inline">
                                    @csrf
                                    <button id="primairy" type="submit" class="logout-button">Uitloggen</button>
                                </form>
                            </li>
                        </ul>
                        <div class="user-header">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=random" alt="Avatar" class="user-avatar">
                            <div class="user-info">
                                <span class="user-name">{{ auth()->user()->name }}</span>
                                <span class="user-email">{{ auth()->user()->email }}</span>
                            </div>
                            <button class="user-menu-toggle" onclick="toggleUserMenu()">⋮</button>
                        </div>
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
</body>
</html>