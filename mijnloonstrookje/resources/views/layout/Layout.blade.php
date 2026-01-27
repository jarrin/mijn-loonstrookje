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
                    'administration.company.employees',
                    'employer.employee.documents', // When admin views employee documents
                    'documents.deleted', // When viewing deleted documents with company or employee context
                ];
                
                if (in_array(request()->route()->getName(), $companyRoutes) && isset($company)) {
                    $brandingCompany = $company;
                }
            }
            // For employer: also check deleted documents page
            elseif ($user->role === 'employer' && request()->route()->getName() === 'documents.deleted') {
                $brandingCompany = $user->company;
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
                        @php
                            // Check if viewing a specific company's employees or employee documents
                            $viewingCompany = in_array(request()->route()->getName(), [
                                'administration.company.employees',
                                'employer.employee.documents',
                                'documents.deleted'
                            ]) && (isset($company) || isset($employee));
                            
                            // Determine routes based on context
                            if ($viewingCompany) {
                                // When viewing a specific company, only show medewerkers tab
                                $companyForRoute = $company ?? (isset($employee) ? $employee->company : null);
                                if ($companyForRoute) {
                                    $employeesRoute = route('administration.company.employees', $companyForRoute->id);
                                    $employeesActive = request()->routeIs('administration.company.employees') || request()->routeIs('employer.employee.documents') || request()->routeIs('documents.deleted');
                                }
                            } else {
                                // When on dashboard, only show home tab
                                $homeRoute = route('administration.dashboard');
                                $homeActive = request()->routeIs('administration.dashboard');
                            }
                        @endphp
                        
                        @if($viewingCompany)
                            {{-- Only show Medewerkers tab when viewing a company --}}
                            <a href="{{ $employeesRoute }}" class="{{ $employeesActive ? 'active' : '' }}">
                                {!! file_get_contents(resource_path('assets/icons/users.svg')) !!}
                                <span>Medewerkers</span>
                            </a>
                        @else
                            {{-- Only show Home tab when on dashboard --}}
                            <a href="{{ $homeRoute }}" class="{{ $homeActive ? 'active' : '' }}">
                                {!! file_get_contents(resource_path('assets/icons/home.svg')) !!}
                                <span>Home</span>
                            </a>
                        @endif
                    @endif

                    {{-- Werkgever links --}}
                    @if(auth()->user()->hasRole('employer'))
                        <a href="{{ route('employer.dashboard') }}" class="{{ request()->routeIs('employer.dashboard') ? 'active' : '' }}">
                            {!! file_get_contents(resource_path('assets/icons/home.svg')) !!}
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('employer.employees') }}" class="{{ request()->routeIs('employer.employees') || request()->routeIs('employer.employee.documents') || (request()->routeIs('documents.deleted') && request()->query('employee')) ? 'active' : '' }}">
                            {!! file_get_contents(resource_path('assets/icons/users.svg')) !!}
                            <span>Werknemers</span>
                        </a>
                        <a href="{{ route('employer.documents') }}" class="{{ request()->routeIs('employer.documents') || (request()->routeIs('documents.deleted') && !request()->query('employee')) ? 'active' : '' }}">
                            {!! file_get_contents(resource_path('assets/icons/documents.svg')) !!}
                            <span>Documenten</span>
                        </a>
                        <a href="{{ route('employer.invoices') }}" class="{{ request()->routeIs('employer.invoices*') ? 'active' : '' }}">
                            {!! file_get_contents(resource_path('assets/icons/invoice.svg')) !!}
                            <span>Facturen</span>
                        </a>
                        <a href="{{ route('employer.admin-offices') }}" class="{{ request()->routeIs('employer.admin-offices') ? 'active' : '' }}">
                            {!! file_get_contents(resource_path('assets/icons/briefcase.svg')) !!}
                            <span>Administratiebureau's</span>
                        </a>
                    @endif
                    
                    {{-- Medewerker links --}}
                    @if(auth()->user()->hasRole('employee'))
                        <a href="{{ route('employee.documents') }}" class="{{ request()->routeIs('employee.documents') ? 'active' : '' }}">
                            {!! file_get_contents(resource_path('assets/icons/documents.svg')) !!}
                            <span>Mijn Documenten</span>
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
                                    <button type="submit" class="logout-button">Uitloggen</button>
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

    <!-- Toast Notification Container -->
    <div id="toastContainer" class="toast-container"></div>

    @if(session('success') || session('error') || session('info') || session('warning'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                showToast({
                    message: '{{ session('success') ?? session('error') ?? session('info') ?? session('warning') }}',
                    type: '{{ session('success') ? 'success' : (session('error') ? 'error' : (session('warning') ? 'warning' : 'info')) }}'
                });
            });
        </script>
    @endif

    <script>
        let toastCount = 0;

        function showToast({ message, type = 'info', duration = 4000 }) {
            const container = document.getElementById('toastContainer');
            const toastId = `toast-${++toastCount}`;
            
            const icons = {
                success: '✓',
                error: '✕',
                info: 'ℹ',
                warning: '⚠'
            };
            
            const toast = document.createElement('div');
            toast.id = toastId;
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `
                <div class="toast-icon">${icons[type] || icons.info}</div>
                <div class="toast-content">
                    <div class="toast-message">${message}</div>
                </div>
                <button onclick="closeToast('${toastId}')" class="toast-close">×</button>
            `;
            
            container.appendChild(toast);
            
            // Trigger animation
            setTimeout(() => {
                toast.classList.add('show');
            }, 10);
            
            // Auto-hide after duration
            if (duration > 0) {
                setTimeout(() => {
                    closeToast(toastId);
                }, duration);
            }
            
            return toastId;
        }

        function closeToast(toastId) {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.classList.remove('show');
                toast.classList.add('hide');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }
        }

        // Make showToast globally available
        window.showToast = showToast;
        window.closeToast = closeToast;
    </script>

    @stack('scripts')
</body>
</html>