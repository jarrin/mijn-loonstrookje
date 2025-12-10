@extends('layout.empty')

@section('title', 'Account Activeren - Mijn Loonstrookje')

@section('content')
<section class="loginPage">
    <div class="loginContainer">
        <div class="loginHeader">
            <h1>Mijn Loonstrookje</h1>
            <p>Account Activeren</p>
        </div>
        
        @if(session('error'))
            <div class="error-message">
                {{ session('error') }}
            </div>
        @endif
        
        <form action="{{ route('invitation.register', $invitation->token) }}" method="POST" class="loginForm">
            @csrf
            
            <div class="loginFormGroup">
                <label for="email">E-mailadres</label>
                <input 
                    type="email" 
                    id="email" 
                    value="{{ $invitation->email }}"
                    disabled
                >
                <span class="form-hint">Dit e-mailadres kan niet worden gewijzigd</span>
            </div>
            
            <div class="loginFormGroup">
                <label for="name">Volledige Naam <span class="required">*</span></label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}"
                    required
                    autofocus
                    placeholder="Jan Jansen"
                >
                @error('name')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="loginFormGroup">
                <label for="password">Wachtwoord <span class="required">*</span></label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                    minlength="8"
                >
                @error('password')
                    <span class="error">{{ $message }}</span>
                @enderror
                <span class="form-hint">Minimaal 8 tekens</span>
            </div>
            
            <div class="loginFormGroup">
                <label for="password_confirmation">Bevestig Wachtwoord <span class="required">*</span></label>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    required
                    minlength="8"
                >
            </div>
            
            <button type="submit" class="loginSubmitButton">Account Activeren</button>
        </form>
    </div>
</section>
@endsection
