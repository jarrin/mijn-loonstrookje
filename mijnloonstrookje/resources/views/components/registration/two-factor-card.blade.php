@props([
    'emailVerified' => false,
    'has2FASecret' => false,
    'has2FAConfirmed' => false,
    'user' => null
])

@php
    $user = $user ?? auth()->user();
@endphp

<div class="border-2 {{ !$emailVerified ? 'border-gray-200 bg-gray-50' : ($has2FAConfirmed ? 'border-green-200 bg-green-50' : 'border-blue-500') }} rounded-xl p-5 mb-6">
    <div class="flex items-start gap-4">
        <div class="w-10 h-10 {{ $has2FAConfirmed ? 'bg-green-100' : 'bg-purple-100' }} rounded-lg flex items-center justify-center flex-shrink-0">
            @if($has2FAConfirmed)
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            @else
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            @endif
        </div>
        <div class="flex-1">
            <h3 class="font-semibold text-gray-900">Activeer tweestapsverificatie</h3>
            @if($has2FAConfirmed)
                <p class="text-sm text-green-600 mt-1">✓ Tweestapsverificatie is geactiveerd</p>
            @elseif(!$emailVerified)
                <p class="text-sm text-gray-500 mt-1">Beveilig je account met tweestapsverificatie.</p>
                <p class="text-xs text-gray-400 mt-2 italic">Verifieer eerst je e-mailadres</p>
            @elseif($has2FASecret)
                <p class="text-sm text-gray-500 mt-1">Scan de QR-code met je authenticator app:</p>
                <div class="flex justify-center bg-white p-4 rounded-lg border border-gray-200 my-4">
                    {!! $user->twoFactorQrCodeSvg() !!}
                </div>
                <form method="POST" action="{{ url('/user/confirmed-two-factor-authentication') }}">
                    @csrf
                    <label class="block text-sm font-medium text-gray-700 mb-2">Voer de 6-cijferige code in:</label>
                    
                    @if($errors->confirmTwoFactorAuthentication->any())
                        <div class="mb-3 p-3 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-sm text-red-600">{{ $errors->confirmTwoFactorAuthentication->first('code') }}</p>
                        </div>
                    @endif
                    
                    <input type="text" name="code" placeholder="123456" maxlength="6" required autofocus
                           class="block w-full px-3 py-3 border border-gray-200 rounded-lg text-center text-xl tracking-widest focus:outline-none focus:ring-2 focus:ring-blue-500">
                    
                    <button type="submit" class="mt-4 w-full py-3 px-4 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg">
                        Bevestig code
                    </button>
                </form>
            @else
                <p class="text-sm text-gray-500 mt-1">Beveilig je account met tweestapsverificatie via Google Authenticator of Authy.</p>
                <form method="POST" action="{{ url('/user/two-factor-authentication') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 border-2 border-purple-500 text-sm font-medium rounded-lg text-purple-500 bg-white hover:bg-purple-50">
                        2FA activeren
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
