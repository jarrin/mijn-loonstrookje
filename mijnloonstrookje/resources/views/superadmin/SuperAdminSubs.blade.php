@extends('layout.Layout')

@section('title', 'Abonnementen - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="text-2xl mb-4">Abonnementen Beheer</h1>
    <p>Hier komen alle abonnementen te staan.</p>
    
    <div class="mt-6 space-x-4">
        <a href="{{ route('superadmin.dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Terug naar Dashboard</a>
        <a href="{{ route('superadmin.logs') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Logs</a>
        <a href="{{ route('superadmin.facturation') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Facturatie</a>
    </div>
</section>
<section>

    <h2>Alle abonnementen</h2>

    <div style="display: flex; gap: 20px;">
    @foreach ($subscriptions as $subscription)
        @php
            $isEditing = request('edit') == $subscription->id;
        @endphp
        <div class="subscription-tile" style="border: 1px solid #ddd; border-radius: 8px; padding: 16px; width: 250px;">
            @if(! $isEditing)
                {{-- Alleen lezen modus --}}
                <p><strong>Naam:</strong></p>
                <p>{{ $subscription->name }}</p>

                <p><strong>Kernpunten:</strong></p>
                <ul>
                    @if($subscription->feature_1)
                        <li>{{ $subscription->feature_1 }}</li>
                    @endif
                    @if($subscription->feature_2)
                        <li>{{ $subscription->feature_2 }}</li>
                    @endif
                    @if($subscription->feature_3)
                        <li>{{ $subscription->feature_3 }}</li>
                    @endif
                </ul>

                <p><strong>Prijs:</strong></p>
                <p>€ {{ number_format($subscription->price, 2, ',', '.') }}</p>

                <p><strong>Plan:</strong></p>
                <p>{{ $subscription->subscription_plan }}</p>

                <form method="GET" action="{{ route('superadmin.subscriptions') }}">
                    <input type="hidden" name="edit" value="{{ $subscription->id }}">
                    <button type="submit">Bewerken</button>
                </form>
            @else
                {{-- Bewerk modus --}}
                <form method="POST" action="{{ route('superadmin.subscriptions.update', $subscription->id) }}">
                    @csrf
                    @method('PUT')

                    <div>
                        <p><strong>Naam:</strong></p>
                        <p>
                            <input type="text" name="name" value="{{ old('name', $subscription->name) }}">
                        </p>
                    </div>

                    <div>
                        <p><strong>Kernpunten:</strong></p>
                        <ul>
                            <li>
                                <input type="text" name="feature_1" value="{{ old('feature_1', $subscription->feature_1) }}">
                            </li>
                            <li>
                                <input type="text" name="feature_2" value="{{ old('feature_2', $subscription->feature_2) }}">
                            </li>
                            <li>
                                <input type="text" name="feature_3" value="{{ old('feature_3', $subscription->feature_3) }}">
                            </li>
                        </ul>
                    </div>

                    <div>
                        <p><strong>Prijs:</strong></p>
                        <p>
                            <input type="number" step="0.01" name="price" value="{{ old('price', $subscription->price) }}">
                        </p>
                    </div>

                    <div>
                        <p><strong>Plan:</strong></p>
                        <p>
                            <input type="text" name="subscription_plan" value="{{ old('subscription_plan', $subscription->subscription_plan) }}">
                        </p>
                    </div>

                    <div>
                        <button type="submit">Opslaan</button>
                    </div>
                </form>
            @endif
        </div>
    @endforeach
    </div>

</section>
@endsection
