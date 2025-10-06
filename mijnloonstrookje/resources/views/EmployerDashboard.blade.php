@extends('layout.Layout')

@section('title', 'Werkgever Dashboard - Mijn Loonstrookje')

@section('content')
<div class="row">
    <div class="col-12">
        <h1>Werkgever Dashboard</h1>
        <p class="lead">Welkom {{ auth()->user()->name }}, beheer hier je medewerkers en loonstrookjes.</p>
    </div>
</div>

@endsection