@extends('layout.Layout')

@section('title', 'Profiel Instellingen - Mijn Loonstrookje')

@section('content')
<div class="settings-page">
    <div class="settings-container">
        <h1 class="settings-title">Profiel Instellingen</h1>

        <!-- Password Change Section -->
        <div class="settings-card">
            <h2 class="settings-card-title">Wachtwoord Wijzigen</h2>
            <form method="POST" action="{{ route('profile.password.update') }}">
                @csrf
                
                <div class="form-group">
                    <label for="current_password" class="form-label">Huidig Wachtwoord</label>
                    <input type="password" 
                           id="current_password" 
                           name="current_password" 
                           class="form-input @error('current_password') form-input-error @enderror"
                           required>
                    @error('current_password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Nieuw Wachtwoord</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="form-input @error('password') form-input-error @enderror"
                           required>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Bevestig Nieuw Wachtwoord</label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           class="form-input"
                           required>
                </div>

                <button type="submit" class="btn btn-primary">
                    Wachtwoord Bijwerken
                </button>
            </form>
        </div>

        <!-- Two-Factor Authentication Section -->
        <div class="settings-card">
            <h2 class="settings-card-title">Twee-factor Authenticatie</h2>
            
            @if (auth()->user()->two_factor_secret)
                @if (auth()->user()->two_factor_confirmed_at)
                    <div class="two-factor-section">
                        <div class="status-badge-wrapper">
                            <span class="status-badge status-badge-enabled">
                                <svg class="status-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Ingeschakeld
                            </span>
                        </div>
                        <p class="two-factor-description">Je account is beveiligd met twee-factor authenticatie.</p>
                    </div>

                    <div class="two-factor-qr-section">
                        <h3 class="section-subtitle">QR Code</h3>
                        <p class="section-description">Je kunt deze QR-code opnieuw scannen als dat nodig is:</p>
                        <div class="qr-code-wrapper">
                            {!! auth()->user()->twoFactorQrCodeSvg() !!}
                        </div>
                    </div>

                    <div class="two-factor-recovery-section">
                        <h3 class="section-subtitle">Herstelcodes</h3>
                        <p class="section-description">Bewaar deze herstelcodes op een veilige plaats.</p>
                        
                        @if (session('two-factor.recovery-codes'))
                            <div class="recovery-codes-container">
                                @foreach (session('two-factor.recovery-codes') as $code)
                                    <code class="recovery-code">{{ $code }}</code>
                                @endforeach
                            </div>
                        @endif
                        
                        <form method="POST" action="{{ route('profile.two-factor-recovery-codes') }}">
                            @csrf
                            <button type="submit" class="btn btn-secondary">
                                Nieuwe herstelcodes genereren
                            </button>
                        </form>
                    </div>

                    @if (session()->has('pending_subscription_id'))
                        <div class="alert alert-info">
                            <p class="alert-title">Je bent bijna klaar!</p>
                            <p class="alert-text">Je account is beveiligd met twee-factor authenticatie. Nu kun je verder met je betaling.</p>
                            <a href="{{ route('payment.start', ['subscription' => session('pending_subscription_id')]) }}" 
                               class="btn btn-success">
                                Ga naar betaling
                            </a>
                        </div>
                    @endif

                    <form method="POST" action="{{ url('/user/two-factor-authentication') }}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger" type="submit" onclick="return confirm('Weet je zeker dat je twee-factor authenticatie wilt uitschakelen?')">
                            Twee-factor authenticatie uitschakelen
                        </button>
                    </form>
                @else
                    <div class="alert alert-warning">
                        <p class="alert-title">⚠️ Beveiliging vereist</p>
                        <p class="alert-text">Twee-factor authenticatie moet worden bevestigd voor je account.</p>
                    </div>

                    <div class="two-factor-qr-section">
                        <h3 class="section-subtitle">QR Code</h3>
                        <p class="section-description">Scan deze QR-code met je authenticator app:</p>
                        <div class="qr-code-wrapper">
                            {!! auth()->user()->twoFactorQrCodeSvg() !!}
                        </div>
                    </div>

                    <form method="POST" action="{{ url('/user/confirmed-two-factor-authentication') }}">
                        @csrf
                        <div class="form-group">
                            <label for="code" class="form-label">Bevestigingscode</label>
                            <input type="text" 
                                   id="code" 
                                   name="code" 
                                   placeholder="123456" 
                                   class="form-input"
                                   required>
                            @error('code')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">
                            Bevestigen en doorgaan
                        </button>
                    </form>
                @endif
            @else
                <div class="two-factor-section">
                    <div class="status-badge-wrapper">
                        <span class="status-badge status-badge-disabled">
                            <svg class="status-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            Uitgeschakeld
                        </span>
                    </div>
                    <p class="two-factor-description">Voeg een extra beveiligingslaag toe aan je account door twee-factor authenticatie in te schakelen.</p>
                </div>
                
                <form method="POST" action="{{ url('/user/two-factor-authentication') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        Twee-factor authenticatie inschakelen
                    </button>
                </form>
            @endif
        </div>

        <!-- Role-Specific Settings Section -->
        @if (auth()->user()->role === 'employer' && $company)
        <div class="settings-card">
            <h2 class="settings-card-title">Bedrijfsinstellingen</h2>
            <p class="settings-card-description">Pas de huisstijl van je bedrijf aan. Deze instellingen bepalen hoe je bedrijf eruitziet in het systeem.</p>
            
            <form method="POST" action="{{ route('profile.branding.update') }}" enctype="multipart/form-data">
                @csrf
                
                <div class="form-group">
                    <label for="primary_color" class="form-label">Primaire Kleur</label>
                    <p class="form-hint">Deze kleur wordt gebruikt voor knoppen, links en andere accentelementen.</p>
                    <div class="color-picker-group">
                        <input type="color" 
                               id="primary_color" 
                               name="primary_color" 
                               value="{{ $company->primary_color ?? '#3B82F6' }}"
                               class="color-picker-input"
                               required>
                        <input type="text" 
                               id="primary_color_text" 
                               value="{{ $company->primary_color ?? '#3B82F6' }}"
                               class="color-text-input"
                               readonly>
                        <span class="color-hint-text">Secundaire kleur wordt automatisch berekend (60% opacity)</span>
                    </div>
                    @error('primary_color')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="logo" class="form-label">Bedrijfslogo</label>
                    <p class="form-hint">Upload een logo voor je bedrijf (max 2MB). Ondersteunde formaten: PNG, JPG, SVG</p>
                    
                    @if($company->logo_path)
                        <div class="logo-preview-wrapper">
                            <p class="logo-preview-label">Huidige logo:</p>
                            <img src="{{ asset('storage/' . $company->logo_path) }}" 
                                 alt="Company Logo" 
                                 class="logo-preview-image">
                        </div>
                    @endif
                    
                    <input type="file" 
                           id="logo" 
                           name="logo" 
                           accept="image/png,image/jpeg,image/jpg,image/svg+xml"
                           class="file-input">
                    @error('logo')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    Bedrijfsinstellingen Opslaan
                </button>
            </form>
        </div>
        @endif
    </div>
</div>

<script>
    // Sync color picker with text input
    const colorPicker = document.getElementById('primary_color');
    const colorText = document.getElementById('primary_color_text');
    
    if (colorPicker && colorText) {
        colorPicker.addEventListener('input', function() {
            colorText.value = this.value.toUpperCase();
        });
    }
</script>
@endsection
