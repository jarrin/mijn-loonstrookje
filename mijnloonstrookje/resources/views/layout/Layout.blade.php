<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mijn Loonstrookje')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @php
        // Get company branding based on user role and context
        $brandingCompany = null;
        if (auth()->check()) {
            $user = auth()->user();
            
            // For employer and employee: use their company
            if ($user->role === 'employer' || $user->role === 'employee') {
                $brandingCompany = $user->company;
            }
            // For admin office: only apply branding when viewing specific company pages
            elseif ($user->role === 'administration_office') {
                // Only apply branding on specific company routes, not on dashboard
                $companyRoutes = [
                    'administration.company.show',
                    'administration.company.employees',
                    'administration.company.documents',
                    'employer.employee.documents' // When admin views employee documents
                ];
                
                if (in_array(request()->route()->getName(), $companyRoutes) && isset($company)) {
                    $brandingCompany = $company;
                }
            }
        }
        
        $primaryColor = $brandingCompany && $brandingCompany->primary_color ? $brandingCompany->primary_color : '#3B82F6';
        $secondaryColor = $brandingCompany ? $brandingCompany->secondary_color : 'rgba(59, 130, 246, 0.6)';
        
        // Calculate a lighter background color (15% opacity) for hover/active states
        if ($brandingCompany && $brandingCompany->primary_color) {
            $hex = str_replace('#', '', $brandingCompany->primary_color);
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
            $lightBgColor = "rgba($r, $g, $b, 0.15)";
        } else {
            $lightBgColor = 'rgba(59, 130, 246, 0.15)';
        }
        
        $logoPath = $brandingCompany && $brandingCompany->logo_path 
            ? asset('storage/' . $brandingCompany->logo_path) 
            : asset('images/loonstrookje-breed-logo-upscale-transparent.png');
    @endphp
    
    <style>
        :root {
            --primary-color: {{ $primaryColor }};
            --secondary-color: {{ $secondaryColor }};
            --light-bg-color: {{ $lightBgColor }};
        }
    </style>
</head>
<body>
    <nav class="main-side-nav">
        <div class="logo"> 
            <img src="{{ $logoPath }}" alt="Logo">
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
                        <a href="{{ route('employer.documents') }}" class="{{ request()->routeIs('employer.documents') ? 'active' : '' }}">
                            {!! file_get_contents(resource_path('assets/icons/documents.svg')) !!}
                            <span>Documenten</span>
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
                        <a href="{{ route('employee.documents') }}" class="{{ request()->routeIs('employee.documents') ? 'active' : '' }}">
                            {!! file_get_contents(resource_path('assets/icons/documents.svg')) !!}
                            <span>Documenten</span>
                        </a>
                    @endif
                @endauth
            </div>
            
            <div class="button-nav">
                @auth
                    <div class="user-menu-container">
                        <ul class="user-menu-dropdown" id="userMenuDropdown">
                            <li>
                                <a href="{{ route('profile.settings') }}" class="dropdown-link">Profiel Instellingen</a>
                            </li>
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

    <!-- Toast Notification -->
    @if(session('success') || session('error') || session('info'))
        <div id="toast" class="toast toast-{{ session('success') ? 'success' : (session('error') ? 'error' : 'info') }}">
            <div class="toast-content">
                <span class="toast-icon">
                    @if(session('success'))
                        ✓
                    @elseif(session('error'))
                        ✕
                    @else
                        ℹ
                    @endif
                </span>
                <span class="toast-message">
                    {{ session('success') ?? session('error') ?? session('info') }}
                </span>
            </div>
            <button onclick="closeToast()" class="toast-close">×</button>
        </div>
        <script>
            // Show toast and auto-hide after 5 seconds
            document.addEventListener('DOMContentLoaded', function() {
                const toast = document.getElementById('toast');
                if (toast) {
                    setTimeout(() => {
                        toast.classList.add('show');
                    }, 100);
                    
                    setTimeout(() => {
                        closeToast();
                    }, 5000);
                }
            });
            
            function closeToast() {
                const toast = document.getElementById('toast');
                if (toast) {
                    toast.classList.remove('show');
                    setTimeout(() => {
                        toast.remove();
                    }, 300);
                }
            }
        </script>
    @endif

    @stack('scripts')
</body>
</html>