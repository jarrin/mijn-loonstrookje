@extends('layout.Layout')

@section('title', 'Super Admin Dashboard - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="text-2xl mb-4">Super Admin Dashboard</h1>
    <p>Welkom {{ auth()->user()->name }}, je hebt volledige toegang tot het systeem.</p>
    
    <div class="mt-6 space-x-4">
        <a href="{{ route('superadmin.subscriptions') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Abonnementen</a>
        <a href="{{ route('superadmin.logs') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Logs</a>
        <a href="{{ route('superadmin.facturation') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Facturatie</a>
    </div>
</section>
@endsection