@extends('layout.Layout')

@section('title', 'Bevestig Wachtwoord - Mijn Loonstrookje')

@section('content')
<section>
    <h2 class="text-xl mb-4">Bevestig je wachtwoord</h2>
    <p class="mb-4">Bevestig je wachtwoord voordat je doorgaat met deze beveiligde actie.</p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="mb-4">
            <label for="password" class="block">Wachtwoord</label>
            <input type="password" id="password" name="password" required autofocus class="block w-80 p-2">
            @error('password')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div class="flex gap-4">
            <a href="{{ url()->previous() }}">Annuleren</a>
            <button type="submit" class="px-4 py-2 cursor-pointer">Bevestigen</button>
        </div>
    </form>
</section>
@endsection