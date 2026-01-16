<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Betaling Custom Abonnement - Mijn Loonstrookje</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Voltooi je bestelling
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Stap 3 van 3: Betaal je custom abonnement
                </p>
            </div>

            <!-- Progress bar -->
            <div class="w-full bg-gray-200 rounded-full h-2.5">
                <div class="bg-blue-600 h-2.5 rounded-full" style="width: 100%"></div>
            </div>

            <div class="bg-white shadow-lg rounded-lg p-8">
                <!-- Custom Subscription details -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Je custom abonnement</h3>
                    <div class="bg-purple-50 rounded-lg p-4 border border-purple-200">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h4 class="text-xl font-bold text-gray-900">Custom Abonnement</h4>
                                <p class="text-sm text-gray-600 mt-1">Speciaal voor jou samengesteld</p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-purple-600">€{{ number_format($customSubscription->price, 2, ',', '.') }}</p>
                                <p class="text-xs text-gray-500">{{ ucfirst($customSubscription->billing_period) }}</p>
                            </div>
                        </div>
                        
                        <div class="mt-4 pt-4 border-t border-purple-200">
                            <ul class="space-y-2 text-sm text-gray-700">
                                <li class="flex items-center">
                                    <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    {{ $customSubscription->max_users }} gebruikers
                                </li>
                                <li class="flex items-center">
                                    <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Onbeperkt documenten
                                </li>
                                <li class="flex items-center">
                                    <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Veilige opslag
                                </li>
                                <li class="flex items-center">
                                    <svg class="h-5 w-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    {{ ucfirst($customSubscription->billing_period) }} betaling
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Payment info -->
                <div class="mb-6">
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-blue-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm text-gray-600">
                            Je wordt doorgestuurd naar een veilige betaalomgeving van Mollie. 
                            Je custom abonnement wordt direct geactiveerd na succesvolle betaling.
                        </p>
                    </div>
                </div>

                <!-- Payment button -->
                <form method="POST" action="{{ route('payment.start.custom', ['customSubscription' => $customSubscription->id]) }}">
                    @csrf
                    <button type="submit" 
                            class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        Betaal €{{ number_format($customSubscription->price, 2, ',', '.') }} via Mollie
                    </button>
                </form>

                <p class="mt-4 text-xs text-center text-gray-500">
                    Beveiligd betalen via 
                    <img src="https://www.mollie.com/external/icons/payment-methods/ideal.svg" alt="iDEAL" class="inline h-4 mx-1">
                    <img src="https://www.mollie.com/external/icons/payment-methods/creditcard.svg" alt="Credit Card" class="inline h-4 mx-1">
                    <img src="https://www.mollie.com/external/icons/payment-methods/paypal.svg" alt="PayPal" class="inline h-4 mx-1">
                </p>
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
