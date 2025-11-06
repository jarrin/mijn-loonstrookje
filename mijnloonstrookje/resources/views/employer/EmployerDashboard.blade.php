@extends('layout.Layout')

@section('title', 'Werkgever Dashboard - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="text-2xl mb-4">Werkgever Dashboard</h1>
    <p>Welkom {{ auth()->user()->name }}, beheer hier je medewerkers en loonstrookjes.</p>
    
    <div class="mt-6 space-x-4">
        <a href="{{ route('employer.employees') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Medewerkers</a>
        <a href="{{ route('employer.documents') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Documenten</a>
        <a href="{{ route('employer.admin-offices') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Administratiekantoren</a>
    </div>
</section>
@endsection