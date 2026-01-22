<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beveilig je account - Mijn Loonstrookje</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Beveilig je account
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Stap 2 van 3: Activeer twee-factor authenticatie
                </p>
            </div>

            <!-- Progress bar -->
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-blue-600 h-2.5 rounded-full" style="width: 66%"></div>
            </div>

            @if (session('error'))
                <div class="rounded-md bg-red-50 p-4">
                    <p class="text-sm text-red-800">{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white shadow-lg rounded-lg p-8">
                @if (auth()->user()->two_factor_secret)
                    @if (auth()->user()->two_factor_confirmed_at)
                        <!-- 2FA al bevestigd -->
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="mt-4 text-lg font-medium text-gray-900">Account beveiligd!</h3>
                            <p class="mt-2 text-sm text-gray-500">Twee-factor authenticatie is succesvol ingeschakeld.</p>
                            
                            @if (session('pending_subscription_id') && auth()->user()->role === 'employer')
                                <a href="{{ route('payment.checkout', ['subscription' => session('pending_subscription_id')]) }}" 
                                   class="mt-6 w-full inline-flex justify-center py-3 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Verder naar betaling →
                                </a>
                            @else
                                @php
                                    $dashboardRoute = match(auth()->user()->role) {
                                        'super_admin' => 'superadmin.dashboard',
                                        'administration_office' => 'administration.dashboard',
                                        'employer' => 'employer.dashboard',
                                        'employee' => 'employee.dashboard',
                                        default => 'employee.dashboard',
                                    };
                                @endphp
                                <a href="{{ route($dashboardRoute) }}" 
                                   class="mt-6 w-full inline-flex justify-center py-3 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    Ga naar dashboard →
                                </a>
                            @endif
                        </div>
                    @else
                        <!-- 2FA nog niet bevestigd -->
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-2">Scan de QR-code</h3>
                            <p class="text-sm text-gray-600 mb-4">
                                Open je authenticator app (zoals Google Authenticator of Authy) en scan de onderstaande QR-code.
                            </p>
                            <div class="flex justify-center bg-white p-4 rounded-lg border-2 border-gray-200">
                                {!! auth()->user()->twoFactorQrCodeSvg() !!}
                            </div>
                        </div>

                        <form method="POST" action="{{ url('/user/confirmed-two-factor-authentication') }}" class="space-y-6">
                            @csrf
                            <div>
                                <label for="code" class="block text-sm font-medium text-gray-700">
                                    Voer de 6-cijferige code in
                                </label>
                                <input type="text" 
                                       id="code" 
                                       name="code" 
                                       placeholder="123456"
                                       maxlength="6"
                                       class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-center text-2xl tracking-widest"
                                       required 
                                       autofocus>
                                @error('code')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" 
                                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Bevestig en ga verder
                            </button>
                        </form>
                    @endif
                @else
                    <!-- 2FA nog niet gestart -->
                    <div class="text-center mb-6">
                        <svg class="mx-auto h-12 w-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Extra beveiliging vereist</h3>
                        <p class="mt-2 text-sm text-gray-600">
                            Ter bescherming van je bedrijfsgegevens is twee-factor authenticatie verplicht.
                        </p>
                    </div>

                    <form method="POST" action="{{ url('/user/two-factor-authentication') }}">
                        @csrf
                        <button type="submit" 
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Twee-factor authenticatie activeren
                        </button>
                    </form>
                @endif
            </div>

            <div class="text-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">
                        Uitloggen
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
