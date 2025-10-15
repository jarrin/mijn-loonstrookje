@extends('layout.Layout')

@section('title', 'Twee-factor Authenticatie - Mijn Loonstrookje')

@section('content')
<<<<<<< HEAD
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
=======
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Twee-factor Authenticatie</h4>
            </div>
            <div class="card-body">
                @if (auth()->user()->two_factor_secret)
                    <div class="alert alert-success">
                        <strong>Twee-factor authenticatie is ingeschakeld.</strong>
                    </div>

                    @if (auth()->user()->two_factor_confirmed_at)
                        <div class="mb-4">
                            <h5>QR Code</h5>
                            <p class="text-muted">Je kunt deze QR-code opnieuw scannen als dat nodig is:</p>
                            <div class="mb-3">
                                {!! auth()->user()->twoFactorQrCodeSvg() !!}
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5>Herstelcodes</h5>
                            <p class="text-muted">Bewaar deze herstelcodes op een veilige plaats.</p>
                            @if (session('two-factor.recovery-codes'))
                                <div class="alert alert-warning">
                                    @foreach (session('two-factor.recovery-codes') as $code)
                                        <code>{{ $code }}</code><br>
                                    @endforeach
                                </div>
                            @endif
                            
                            <form method="POST" action="{{ url('/user/two-factor-recovery-codes') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-secondary">Nieuwe herstelcodes genereren</button>
                            </form>
                        </div>

                        <form method="POST" action="{{ url('/user/two-factor-authentication') }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Weet je zeker dat je twee-factor authenticatie wilt uitschakelen?')">
                                Twee-factor authenticatie uitschakelen
                            </button>
                        </form>
                    @else
                        <div class="alert alert-warning">
                            <strong>Twee-factor authenticatie is nog niet bevestigd.</strong>
                            <p class="mb-0">Scan de QR-code hieronder en bevestig met een code uit je authenticator app.</p>
                        </div>

                        <div class="mb-4">
                            <h5>QR Code</h5>
                            <p class="text-muted">Scan deze QR-code met je authenticator app:</p>
                            <div class="mb-3">
                                {!! auth()->user()->twoFactorQrCodeSvg() !!}
                            </div>
                        </div>

                        <form method="POST" action="{{ url('/user/confirmed-two-factor-authentication') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="code" class="form-label">Bevestigingscode</label>
                                <input type="text" 
                                       class="form-control @error('code') is-invalid @enderror" 
                                       id="code" 
                                       name="code" 
                                       placeholder="123456" 
                                       required>
                                @error('code')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-success">Bevestigen</button>
                        </form>
                    @endif
                @else
                    <div class="alert alert-info">
                        <strong>Twee-factor authenticatie is uitgeschakeld.</strong>
                    </div>
                    
                    <p>Voeg een extra beveiligingslaag toe aan je account door twee-factor authenticatie in te schakelen.</p>
                    
                    <form method="POST" action="{{ url('/user/two-factor-authentication') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">Twee-factor authenticatie inschakelen</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
>>>>>>> origin/2FaResearchTesting
</div>
@endsection