<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Registratie - Mijn Loonstrookje</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-sky-50 min-h-screen">
    <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-xl">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Account registratie</h1>
                <p class="mt-2 text-gray-500">Voltooi de stappen om je account te activeren</p>
            </div>

            <!-- Step Progress -->
            <div class="flex items-center justify-center mb-10">
                <!-- Step 1 - Completed -->
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <p class="mt-2 text-sm font-medium text-gray-700">Maak account</p>
                </div>

                <div class="w-24 h-0.5 bg-blue-500 mx-2 -mt-6"></div>

                <!-- Step 2 - Active -->
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center ring-4 ring-blue-100">
                        2
                    </div>
                    <p class="mt-2 text-sm font-semibold text-blue-500">Verifieer & beveilig</p>
                </div>

                <div class="w-24 h-0.5 bg-gray-200 mx-2 -mt-6"></div>

                <!-- Step 3 - Inactive -->
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-white border-2 border-gray-200 text-gray-400 flex items-center justify-center">
                        3
                    </div>
                    <p class="mt-2 text-sm font-medium text-gray-400">Betalen</p>
                </div>
            </div>

            <!-- Main Card -->
            <div class="bg-white rounded-2xl shadow-sm p-8">
                <div class="flex justify-center mb-4">
                    <div class="w-14 h-14 bg-purple-100 rounded-full flex items-center justify-center">
                        <svg class="w-7 h-7 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                </div>

                <h2 class="text-xl font-bold text-gray-900 text-center mb-1">Verifieer en beveilig</h2>
                <p class="text-gray-500 text-center mb-8">Bevestig je e-mail en activeer tweestapsverificatie</p>

                @if(session('status'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3">
                        <svg class="w-5 h-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <p class="text-sm text-green-600">{{ session('status') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-sm text-red-600">{{ session('error') }}</p>
                    </div>
                @endif

                @php
                    $user = auth()->user();
                    $emailVerified = $user && $user->hasVerifiedEmail();
                    $has2FASecret = $user && $user->two_factor_secret;
                    $has2FAConfirmed = $user && $user->two_factor_confirmed_at;
                @endphp

                <!-- Email Verification Card -->
                <div class="border-2 {{ $emailVerified ? 'border-green-200 bg-green-50' : 'border-blue-500' }} rounded-xl p-5 mb-4">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 {{ $emailVerified ? 'bg-green-100' : 'bg-blue-100' }} rounded-lg flex items-center justify-center flex-shrink-0">
                            @if($emailVerified)
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">Verifieer je e-mailadres</h3>
                            @if($emailVerified)
                                <p class="text-sm text-green-600 mt-1">✓ Je e-mailadres is geverifieerd</p>
                            @else
                                <p class="text-sm text-gray-500 mt-1">We hebben een verificatielink naar je e-mailadres gestuurd.</p>
                                <p class="text-xs text-gray-400 mt-2">Geen e-mail ontvangen? Check ook je spam folder.</p>

                                <div class="flex gap-3 mt-4">
                                    <a href="{{ route('registration.verify-and-secure') }}" class="inline-flex items-center px-4 py-2 border-2 border-blue-500 text-sm font-medium rounded-lg text-blue-500 bg-white hover:bg-blue-50">
                                        Ik heb geverifieerd
                                    </a>
                                    <form method="POST" action="{{ route('verification.send') }}">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">
                                            Verstuur opnieuw
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- 2FA Card -->
                <div class="border-2 {{ !$emailVerified ? 'border-gray-200 opacity-60' : ($has2FAConfirmed ? 'border-green-200 bg-green-50' : 'border-blue-500') }} rounded-xl p-5 mb-6">
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
                                    {!! auth()->user()->twoFactorQrCodeSvg() !!}
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

                <!-- Navigation -->
                @if($emailVerified && $has2FAConfirmed)
                    <a href="{{ route('payment.custom-checkout', ['customSubscription' => session('pending_custom_subscription_id')]) }}" 
                       class="w-full inline-flex justify-center items-center px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg cursor-pointer transition-colors">
                        Ga verder naar betaling
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                @else
                    <div class="text-center">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-6 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 cursor-pointer">
                                Uitloggen
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>
