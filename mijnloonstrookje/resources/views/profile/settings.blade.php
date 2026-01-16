@extends('layout.Layout')

@section('title', 'Profiel Instellingen - Mijn Loonstrookje')

@section('content')
<div class="min-h-screen bg-gray-100 py-8">
    <div class="max-w-4xl mx-auto px-4">
        <h1 class="text-3xl font-bold mb-8">Profiel Instellingen</h1>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 p-4 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 p-4 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <!-- Password Change Section -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Wachtwoord Wijzigen</h2>
            <form method="POST" action="{{ route('profile.password.update') }}">
                @csrf
                
                <div class="mb-4">
                    <label for="current_password" class="block text-gray-700 font-semibold mb-2">Huidig Wachtwoord</label>
                    <input type="password" 
                           id="current_password" 
                           name="current_password" 
                           class="border border-gray-300 rounded px-4 py-2 w-full @error('current_password') border-red-500 @enderror"
                           required>
                    @error('current_password')
                        <div class="text-red-500 mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-semibold mb-2">Nieuw Wachtwoord</label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           class="border border-gray-300 rounded px-4 py-2 w-full @error('password') border-red-500 @enderror"
                           required>
                    @error('password')
                        <div class="text-red-500 mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block text-gray-700 font-semibold mb-2">Bevestig Nieuw Wachtwoord</label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           class="border border-gray-300 rounded px-4 py-2 w-full"
                           required>
                </div>

                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                    Wachtwoord Bijwerken
                </button>
            </form>
        </div>

        <!-- Two-Factor Authentication Section -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Twee-factor Authenticatie</h2>
            
            @if (auth()->user()->two_factor_secret)
                @if (auth()->user()->two_factor_confirmed_at)
                    <div class="mb-4">
                        <div class="flex items-center mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                Ingeschakeld
                            </span>
                        </div>
                        <p class="text-gray-600 mb-4">Je account is beveiligd met twee-factor authenticatie.</p>
                    </div>

                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-700 mb-2">QR Code</h3>
                        <p class="text-gray-600 mb-3">Je kunt deze QR-code opnieuw scannen als dat nodig is:</p>
                        <div class="bg-gray-50 p-4 rounded inline-block">
                            {!! auth()->user()->twoFactorQrCodeSvg() !!}
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-700 mb-2">Herstelcodes</h3>
                        <p class="text-gray-600 mb-3">Bewaar deze herstelcodes op een veilige plaats.</p>
                        
                        @if (session('two-factor.recovery-codes'))
                            <div class="bg-gray-50 p-4 rounded mb-3">
                                @foreach (session('two-factor.recovery-codes') as $code)
                                    <code class="block text-sm font-mono mb-1">{{ $code }}</code>
                                @endforeach
                            </div>
                        @endif
                        
                        <form method="POST" action="{{ url('/user/two-factor-recovery-codes') }}">
                            @csrf
                            <button type="submit" class="border border-gray-500 hover:bg-gray-100 px-4 py-2 rounded">
                                Nieuwe herstelcodes genereren
                            </button>
                        </form>
                    </div>

                    @if (session()->has('pending_subscription_id'))
                        <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded">
                            <p class="text-blue-800 font-semibold mb-3">Je bent bijna klaar!</p>
                            <p class="text-blue-700 mb-4">Je account is beveiligd met twee-factor authenticatie. Nu kun je verder met je betaling.</p>
                            <a href="{{ route('payment.start', ['subscription' => session('pending_subscription_id')]) }}" 
                               class="inline-block bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                                Ga naar betaling
                            </a>
                        </div>
                    @endif

                    <form method="POST" action="{{ url('/user/two-factor-authentication') }}">
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

                    <div class="mb-6">
                        <h3 class="font-semibold text-gray-700 mb-2">QR Code</h3>
                        <p class="text-gray-600 mb-3">Scan deze QR-code met je authenticator app:</p>
                        <div class="bg-gray-50 p-4 rounded inline-block">
                            {!! auth()->user()->twoFactorQrCodeSvg() !!}
                        </div>
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
                        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                            Bevestigen en doorgaan
                        </button>
                    </form>
                @endif
            @else
                <div class="mb-4">
                    <div class="flex items-center mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-gray-100 text-gray-800">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            Uitgeschakeld
                        </span>
                    </div>
                    <p class="text-gray-600 mb-4">Voeg een extra beveiligingslaag toe aan je account door twee-factor authenticatie in te schakelen.</p>
                </div>
                
                <form method="POST" action="{{ url('/user/two-factor-authentication') }}">
                    @csrf
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                        Twee-factor authenticatie inschakelen
                    </button>
                </form>
            @endif
        </div>

        <!-- Role-Specific Settings Section -->
        @if (auth()->user()->role === 'employer' && $company)
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Bedrijfsinstellingen</h2>
            <p class="text-gray-600 mb-6">Pas de huisstijl van je bedrijf aan. Deze instellingen bepalen hoe je bedrijf eruitziet in het systeem.</p>
            
            <form method="POST" action="{{ route('profile.branding.update') }}" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-6">
                    <label for="primary_color" class="block text-gray-700 font-semibold mb-2">Primaire Kleur</label>
                    <p class="text-sm text-gray-600 mb-3">Deze kleur wordt gebruikt voor knoppen, links en andere accentelementen.</p>
                    <div class="flex items-center gap-4">
                        <input type="color" 
                               id="primary_color" 
                               name="primary_color" 
                               value="{{ $company->primary_color ?? '#3B82F6' }}"
                               class="h-12 w-24 rounded border border-gray-300 cursor-pointer"
                               required>
                        <input type="text" 
                               id="primary_color_text" 
                               value="{{ $company->primary_color ?? '#3B82F6' }}"
                               class="border border-gray-300 rounded px-4 py-2 w-32 font-mono text-sm"
                               readonly>
                        <span class="text-sm text-gray-500">Secundaire kleur wordt automatisch berekend (60% opacity)</span>
                    </div>
                    @error('primary_color')
                        <div class="text-red-500 mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="logo" class="block text-gray-700 font-semibold mb-2">Bedrijfslogo</label>
                    <p class="text-sm text-gray-600 mb-3">Upload een logo voor je bedrijf (max 2MB). Ondersteunde formaten: PNG, JPG, SVG</p>
                    
                    @if($company->logo_path)
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-2">Huidige logo:</p>
                            <img src="{{ asset('storage/' . $company->logo_path) }}" 
                                 alt="Company Logo" 
                                 class="h-16 object-contain border border-gray-200 rounded p-2 bg-white">
                        </div>
                    @endif
                    
                    <input type="file" 
                           id="logo" 
                           name="logo" 
                           accept="image/png,image/jpeg,image/jpg,image/svg+xml"
                           class="border border-gray-300 rounded px-4 py-2 w-full">
                    @error('logo')
                        <div class="text-red-500 mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
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
