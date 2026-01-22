@props([
    'emailVerified' => false,
    'refreshUrl' => null
])

@php
    $refreshUrl = $refreshUrl ?? request()->url();
@endphp

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
                    <a href="{{ $refreshUrl }}" class="inline-flex items-center px-4 py-2 border-2 border-blue-500 text-sm font-medium rounded-lg text-blue-500 bg-white hover:bg-blue-50">
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
