@extends('layout.Layout')

@section('title', 'Inloggen - Mijn Loonstrookje')

@section('content')
<section>
    <h2 class="text-xl mb-4">Inloggen</h2>
    
    <form method="POST" action="{{ route('login') }}">
        @csrf
        
        <div class="mb-4">
            <label for="email" class="block">E-mailadres</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus class="block w-80 p-2">
            @error('email')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="block">Wachtwoord</label>
            <input type="password" id="password" name="password" required class="block w-80 p-2">
            @error('password')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <input type="checkbox" id="remember" name="remember" class="inline-block">
            <label for="remember" class="inline-block ml-2">Onthoud mij</label>
        </div>

        <button type="submit" class="px-4 py-2 cursor-pointer">Inloggen</button>
    </form>
</section>
@endsection