@extends('layout.empty')

@section('title', 'Inloggen - Mijn Loonstrookje')

@section('content')
<section class="loginPage">
    <x-page-background />
    
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

            <button type="submit" class="loginSubmitButton">Inloggen</button>
        </form>
    </div>
</section>
@endsection
