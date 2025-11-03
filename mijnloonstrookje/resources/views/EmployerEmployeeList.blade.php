@extends('layout.Layout')

@section('title', 'Medewerkers - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="text-2xl mb-4">Medewerkers Lijst</h1>
    <p>Hier komen je medewerkers te staan.</p>
    
    <div class="mt-6 space-x-4">
        <a href="{{ route('employer.dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Terug naar Dashboard</a>
        <a href="{{ route('employer.documents') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Documenten</a>
        <a href="{{ route('employer.admin-offices') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Administratiekantoren</a>
    </div>
</section>
@endsection
