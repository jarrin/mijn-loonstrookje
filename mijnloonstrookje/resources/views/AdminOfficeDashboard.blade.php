@extends('layout.Layout')

@section('title', 'Administratiekantoor Dashboard - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="text-2xl mb-4">Administratiekantoor Dashboard</h1>
    <p>Welkom {{ auth()->user()->name }}, beheer hier meerdere werkgevers en hun administratie.</p>
    
    <div class="mt-6 space-x-4">
        <a href="{{ route('administration.employees') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Medewerkers</a>
        <a href="{{ route('administration.documents') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Documenten</a>
    </div>
</section>
@endsection