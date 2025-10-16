@extends('layout.Layout')

@section('title', 'Werkgever Dashboard - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="text-2xl mb-4">Werkgever Dashboard</h1>
    <p>Welkom {{ auth()->user()->name }}, beheer hier je medewerkers en loonstrookjes.</p>
</section>
@endsection