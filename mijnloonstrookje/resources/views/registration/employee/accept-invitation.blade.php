@extends('layout.empty')

@section('title', 'Uitnodiging Accepteren - Mijn Loonstrookje')

@section('content')
<section class="loginPage">
    <div class="loginContainer">
        <div class="loginHeader">
            <h1>Mijn Loonstrookje</h1>
            <p>Uitnodiging Accepteren</p>
        </div>
        
        @if(session('error'))
            <div class="error-message">
                {{ session('error') }}
            </div>
        @endif

        @if(session('info'))
            <div style="background-color: #e3f2fd; border: 1px solid #2196f3; color: #1976d2; padding: 12px; border-radius: 4px; margin-bottom: 20px;">
                {{ session('info') }}
            </div>
        @endif
        
        <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; padding: 16px; border-radius: 8px; margin-bottom: 24px;">
            <p style="margin: 0; color: #374151; font-size: 14px;">
                Je bent uitgenodigd om toegang te krijgen tot <strong>{{ $invitation->company->name ?? 'het bedrijf' }}</strong>.
            </p>
            <p style="margin: 8px 0 0 0; color: #6b7280; font-size: 13px;">
                Log in met je bestaande account om deze uitnodiging te accepteren.
            </p>
        </div>

        <form action="{{ route('invitation.login.accept', $invitation->token) }}" method="POST" class="loginForm">
            @csrf
            
            <div class="loginFormGroup">
                <label for="email">E-mailadres</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email', $invitation->email) }}"
                    required
                    autofocus
                    placeholder="jouw@email.com"
                >
                @error('email')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="loginFormGroup">
                <label for="password">Wachtwoord</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    required
                >
                @error('password')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>
            
            <button type="submit" class="loginSubmitButton">Inloggen en Uitnodiging Accepteren</button>
        </form>

        <div style="text-align: center; margin-top: 20px;">
            <p style="color: #6b7280; font-size: 13px;">
                Deze uitnodiging is geldig tot {{ $invitation->expires_at->format('d-m-Y') }}
            </p>
        </div>
    </div>
</section>
@endsection
