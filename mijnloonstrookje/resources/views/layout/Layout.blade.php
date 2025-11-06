<nav class="main-side-nav">
    <div class="logo">
        <h1>LOGO</h1>
    </div>
    <div class="buttons">
        <div class="nav-tabs">
            @auth
                {{-- SuperAdmin only links --}}
                @if(auth()->user()->hasRole('super_admin'))
                    <a href="{{ route('superadmin.dashboard') }}">Home</a>
                    <a href="{{ route('superadmin.subscriptions') }}">Subscriptions</a>
                    <a href="{{ route('superadmin.logs') }}">Logs</a>
                    <a href="{{ route('superadmin.facturation') }}">Facturatie</a>
                @endif

                {{-- Administratiekantoor only links --}}
                @if(auth()->user()->hasRole('administration_office'))
                    <a href="{{ route('superadmin.dashboard') }}">Home</a>
                    <a href="{{ route('superadmin.subscriptions') }}">Subscriptions</a>
                    <a href="{{ route('superadmin.logs') }}">Logs</a>
                @endif

                {{-- Werkgever only links --}}
                @if(auth()->user()->hasRole('employer'))
                <a href="{{ route('superadmin.facturation') }}">Dashboard</a>
                <a href="{{ route('admin.logs') }}">Werknemers</a>
                <a href="{{ route('admin.EmployerAdminOfficeList') }}">Administratiebureau's</a>
                @endif
                
                {{-- Medewerker links --}}
                @if(auth()->user()->hasRole('employee'))
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