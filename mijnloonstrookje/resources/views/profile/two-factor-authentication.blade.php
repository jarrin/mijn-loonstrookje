@extends('layout.Layout')

@section('title', 'Twee-factor Authenticatie - Mijn Loonstrookje')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="max-w-2xl w-full bg-white shadow-md rounded-lg p-8">
        <h1 class="text-2xl font-bold mb-6">Twee-factor Authenticatie</h1>
    
    @if (auth()->user()->two_factor_secret)
        @if (auth()->user()->two_factor_confirmed_at)
            <p>Twee-factor authenticatie is ingeschakeld.</p>

            <h2>QR Code</h2>
            <p>Je kunt deze QR-code opnieuw scannen als dat nodig is:</p>
            <div>
                {!! auth()->user()->twoFactorQrCodeSvg() !!}
            </div>

            <h2>Herstelcodes</h2>
            <p>Bewaar deze herstelcodes op een veilige plaats.</p>
            @if (session('two-factor.recovery-codes'))
                <div>
                    @foreach (session('two-factor.recovery-codes') as $code)
                        <code>{{ $code }}</code><br>
                    @endforeach
                </div>
            @endif
            
            <form method="POST" action="{{ url('/user/two-factor-recovery-codes') }}">
                @csrf
                <button type="submit" class="border-1 border-gray-500">Nieuwe herstelcodes genereren</button>
            </form>

            @if (session()->has('pending_subscription_id'))
                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded">
                    <p class="text-blue-800 font-semibold mb-3">Je bent bijna klaar!</p>
                    <p class="text-blue-700 mb-4">Je account is beveiligd met twee-factor authenticatie. Nu kun je verder met je betaling.</p>
                    <a href="{{ route('payment.start', ['subscription' => session('pending_subscription_id')]) }}" 
                       class="inline-block bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                        Ga naar betaling
                    </a>
                </div>
            @endif

            <form method="POST" action="{{ url('/user/two-factor-authentication') }}" class="mt-6">
                @csrf
                @method('DELETE')
                <button class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded" type="submit" onclick="return confirm('Weet je zeker dat je twee-factor authenticatie wilt uitschakelen?')">
                    Twee-factor authenticatie uitschakelen
                </button>
            </form>
        @else
            <div class="bg-yellow-50 border border-yellow-200 p-4 rounded mb-6">
                <p class="text-yellow-800 font-semibold">⚠️ Beveiliging vereist</p>
                <p class="text-yellow-700">Twee-factor authenticatie moet worden bevestigd voor je account.</p>
            </div>

            <h2>QR Code</h2>
            <p>Scan deze QR-code met je authenticator app:</p>
            <div>
                {!! auth()->user()->twoFactorQrCodeSvg() !!}
            </div>

            <form method="POST" action="{{ url('/user/confirmed-two-factor-authentication') }}">
                @csrf
                <div class="mb-4">
                    <label for="code" class="block text-gray-700 font-semibold mb-2">Bevestigingscode</label>
                    <input type="text" 
                           id="code" 
                           name="code" 
                           placeholder="123456" 
                           class="border border-gray-300 rounded px-4 py-2 w-full"
                           required>
                    @error('code')
                        <div class="text-red-500 mt-2">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">Bevestigen en doorgaan</button>
            </form>
        @endif
    @else
        <p>Twee-factor authenticatie is uitgeschakeld.</p>
        <p>Voeg een extra beveiligingslaag toe aan je account door twee-factor authenticatie in te schakelen.</p>
        
        <form method="POST" action="{{ url('/user/two-factor-authentication') }}">
            @csrf
            <button type="submit">Twee-factor authenticatie inschakelen</button>
        </form>
    @endif
    </div>
</div>
@endsection