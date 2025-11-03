@extends('layout.Layout')

@section('title', 'Logs - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="text-2xl mb-4">Systeem Logs</h1>
    <p>Hier komen alle systeem logs te staan.</p>
    
    <div class="mt-6 space-x-4">
        <a href="{{ route('superadmin.dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Terug naar Dashboard</a>
        <a href="{{ route('superadmin.subscriptions') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Abonnementen</a>
        <a href="{{ route('superadmin.facturation') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Facturatie</a>
    </div>
</section>
@endsection
