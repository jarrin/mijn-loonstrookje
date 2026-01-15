@extends('layout.empty')

@section('title', 'Inloggen - Mijn Loonstrookje')

@section('content')
@if(auth()->check())
    <script>
        window.location.href = "{{ match(auth()->user()->role) {
            'super_admin' => route('superadmin.dashboard'),
            'administration_office' => route('administration.dashboard'),
            'employer' => route('employer.dashboard'),
            'employee' => route('employee.dashboard'),
            default => route('employee.dashboard'),
        } }}";
    </script>
@endif

<section class="loginPage">
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