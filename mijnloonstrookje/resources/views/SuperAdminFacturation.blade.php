@extends('layout.Layout')

@section('title', 'Facturatie - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="text-2xl mb-4">Facturatie Beheer</h1>
    <p>Hier komt het facturatie overzicht te staan.</p>
    
    <div class="mt-6 space-x-4">
        <a href="{{ route('superadmin.dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Terug naar Dashboard</a>
        <a href="{{ route('superadmin.subscriptions') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Abonnementen</a>
        <a href="{{ route('superadmin.logs') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Logs</a>
    </div>
</section>
@endsection
