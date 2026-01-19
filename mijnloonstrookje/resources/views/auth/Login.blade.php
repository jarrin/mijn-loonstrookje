@extends('layout.empty')

@section('title', 'Inloggen - Mijn Loonstrookje')

@section('content')
@if(auth()->check())
    <script>
        window.location.href = "{{ match(auth()->user()->role) {
            'super_admin' => route('superadmin.dashboard'),
            'administration_office' => route('administration.dashboard'),
            'employer' => route('employer.dashboard'),
            'employee' => route('employee.documents'),
            default => route('employee.documents'),
        } }}";
    </script>
@endif

<section class="loginPage">
    <div class="loginIllustration">
        <svg viewBox="0 0 1920 1080" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice">
            <defs>
                <linearGradient id="bgGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#ffffff;stop-opacity:1" />
                    <stop offset="100%" style="stop-color:#f0f4ff;stop-opacity:1" />
                </linearGradient>
                
                <linearGradient id="waveGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" style="stop-color:#0052cc;stop-opacity:0.08" />
                    <stop offset="100%" style="stop-color:#0052cc;stop-opacity:0" />
                </linearGradient>
                
                <linearGradient id="accentGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" style="stop-color:#0052cc;stop-opacity:0.12" />
                    <stop offset="100%" style="stop-color:#0066ff;stop-opacity:0.06" />
                </linearGradient>

                <filter id="soften">
                    <feGaussianBlur in="SourceGraphic" stdDeviation="2" />
                </filter>
            </defs>
            
            <!-- Background -->
            <rect width="1920" height="1080" fill="url(#bgGradient)"/>
            
            <!-- Large subtle wave at bottom -->
            <path 
                d="M 0,750 Q 480,700 960,740 T 1920,750 L 1920,1080 L 0,1080 Z" 
                fill="url(#waveGradient)"
                opacity="0.6"
            />
            
            <!-- Secondary gentle wave -->
            <path 
                d="M 0,820 Q 240,780 480,820 T 960,820 T 1440,820 T 1920,820 L 1920,1080 L 0,1080 Z" 
                fill="url(#waveGradient)"
                opacity="0.4"
            />
            
            <!-- Abstract document shapes - left side -->
            <g opacity="0.7">
                <!-- Large document outline -->
                <rect x="120" y="150" width="120" height="160" fill="none" stroke="#0052cc" stroke-width="2" opacity="0.15" rx="4"/>
                <!-- Document lines -->
                <line x1="140" y1="180" x2="220" y2="180" stroke="#0052cc" stroke-width="1.5" opacity="0.1"/>
                <line x1="140" y1="200" x2="220" y2="200" stroke="#0052cc" stroke-width="1.5" opacity="0.1"/>
                <line x1="140" y1="220" x2="210" y2="220" stroke="#0052cc" stroke-width="1.5" opacity="0.1"/>
                <line x1="140" y1="240" x2="220" y2="240" stroke="#0052cc" stroke-width="1.5" opacity="0.1"/>
                <line x1="140" y1="260" x2="200" y2="260" stroke="#0052cc" stroke-width="1.5" opacity="0.1"/>
            </g>
            
            <!-- Shield / security symbol - left -->
            <g opacity="0.6" filter="url(#soften)">
                <path 
                    d="M 180,420 L 200,435 L 200,500 Q 200,525 180,540 Q 160,525 160,500 L 160,435 Z" 
                    fill="none" 
                    stroke="#0052cc" 
                    stroke-width="2"
                    opacity="0.2"
                />
            </g>
            
            <!-- Circular accent elements - center area -->
            <g opacity="0.5">
                <!-- Circle 1 -->
                <circle cx="400" cy="250" r="35" fill="none" stroke="#0052cc" stroke-width="1.5" opacity="0.15"/>
                <circle cx="400" cy="250" r="25" fill="none" stroke="#0052cc" stroke-width="1" opacity="0.1"/>
                
                <!-- Circle 2 - right side -->
                <circle cx="1520" cy="320" r="40" fill="none" stroke="#0052cc" stroke-width="1.5" opacity="0.12"/>
                <circle cx="1520" cy="320" r="28" fill="none" stroke="#0052cc" stroke-width="1" opacity="0.08"/>
            </g>
            
            <!-- Document stack - right side -->
            <g opacity="0.6">
                <!-- Back document -->
                <rect x="1570" y="180" width="100" height="130" fill="none" stroke="#0052cc" stroke-width="2" opacity="0.1" rx="3"/>
                <!-- Middle document (offset) -->
                <rect x="1585" y="195" width="100" height="130" fill="none" stroke="#0052cc" stroke-width="2" opacity="0.12" rx="3"/>
                <!-- Front document (offset) -->
                <rect x="1600" y="210" width="100" height="130" fill="none" stroke="#0052cc" stroke-width="2.5" opacity="0.15" rx="3"/>
                <!-- Checkmark on front -->
                <path d="M 1615 255 L 1625 265 L 1645 245" fill="none" stroke="#0052cc" stroke-width="2" opacity="0.2" stroke-linecap="round" stroke-linejoin="round"/>
            </g>
            
            <!-- Grid accent - subtle background pattern -->
            <g opacity="0.03" stroke="#0052cc" stroke-width="1">
                <line x1="0" y1="200" x2="1920" y2="200"/>
                <line x1="0" y1="350" x2="1920" y2="350"/>
                <line x1="0" y1="500" x2="1920" y2="500"/>
                <line x1="0" y1="650" x2="1920" y2="650"/>
            </g>
            
            <!-- Flowing curves - abstract data flow -->
            <g opacity="0.4" fill="none" stroke="#0052cc" stroke-width="1.5" stroke-linecap="round">
                <path d="M 300,650 Q 500,610 700,650" opacity="0.08"/>
                <path d="M 900,680 Q 1100,640 1300,680" opacity="0.08"/>
                <path d="M 1400,650 Q 1600,610 1700,650" opacity="0.08"/>
            </g>
        </svg>
    </div>
    
    <div class="loginContainer">
        <div class="loginHeader">
            <h1>Mijn Loonstrookje</h1>
        </div>
        
        <form method="POST" action="{{ route('login') }}" class="loginForm">
            @csrf
            
            <div class="loginFormGroup">
                <label for="email">E-mailadres</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="loginFormGroup">
                <label for="password">Wachtwoord</label>
                <input type="password" id="password" name="password" required>
                @error('password')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="loginFormRemember">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Onthoud mij</label>
            </div>

            <button type="submit" class="loginSubmitButton">Inloggen</button>
        </form>
    </div>
</section>
@endsection