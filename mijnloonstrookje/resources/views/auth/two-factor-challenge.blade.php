@extends('layout.empty')

@section('title', 'Twee-factor authenticatie - Mijn Loonstrookje')

@section('content')
<section class="loginPage">
    <div class="loginContainer">
        <div class="loginHeader">
            <h1>Twee-factor authenticatie</h1>
            <p style="font-size: 14px; margin-top: 10px; color: #666;">Voer je authenticatiecode in om door te gaan</p>
        </div>
        
        <form method="POST" action="{{ route('two-factor.login') }}" class="loginForm">
            @csrf
            
            <div class="loginFormGroup">
                <label for="code">Authenticatiecode</label>
                <input type="text" id="code" name="code" inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code" required autofocus>
                @error('code')
                    <span class="error">{{ $message }}</span>
                @enderror
                <small style="display: block; margin-top: 5px; color: #666;">Voer de 6-cijferige code uit je authenticatie-app in</small>
            </div>

            <div style="margin: 20px 0; text-align: center; color: #666;">of</div>

            <div class="loginFormGroup">
                <label for="recovery_code">Herstelcode</label>
                <input type="text" id="recovery_code" name="recovery_code" autocomplete="one-time-code">
                @error('recovery_code')
                    <span class="error">{{ $message }}</span>
                @enderror
                <small style="display: block; margin-top: 5px; color: #666;">Gebruik een herstelcode als je geen toegang hebt tot je authenticatie-app</small>
            </div>

            <button type="submit" class="loginSubmitButton">Verifiëren</button>
            
            <div style="text-align: center; margin-top: 15px;">
                <a href="{{ route('login') }}" style="color: #007bff; text-decoration: none;">← Terug naar inloggen</a>
            </div>
        </form>
    </div>
</section>
@endsection
