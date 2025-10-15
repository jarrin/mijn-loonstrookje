@extends('layout.Layout')

@section('title', 'Twee-factor Authenticatie - Mijn Loonstrookje')

@section('content')
<div>
    <h1>Twee-factor Authenticatie</h1>
    
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
                <button type="submit">Nieuwe herstelcodes genereren</button>
            </form>

            <form method="POST" action="{{ url('/user/two-factor-authentication') }}">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Weet je zeker dat je twee-factor authenticatie wilt uitschakelen?')">
                    Twee-factor authenticatie uitschakelen
                </button>
            </form>
        @else
            <p>Twee-factor authenticatie is nog niet bevestigd.</p>
            <p>Scan de QR-code hieronder en bevestig met een code uit je authenticator app.</p>

            <h2>QR Code</h2>
            <p>Scan deze QR-code met je authenticator app:</p>
            <div>
                {!! auth()->user()->twoFactorQrCodeSvg() !!}
            </div>

            <form method="POST" action="{{ url('/user/confirmed-two-factor-authentication') }}">
                @csrf
                <div>
                    <label for="code">Bevestigingscode</label>
                    <input type="text" 
                           id="code" 
                           name="code" 
                           placeholder="123456" 
                           required>
                    @error('code')
                        <div>{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit">Bevestigen</button>
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
@endsection