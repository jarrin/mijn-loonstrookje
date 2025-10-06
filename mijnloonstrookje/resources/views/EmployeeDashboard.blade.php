@extends('layout.Layout')

@section('title', 'Medewerker Dashboard - Mijn Loonstrookje')

@section('content')
<div class="row">
    <div class="col-12">
        <h1>Medewerker Dashboard</h1>
        <p class="lead">Welkom {{ auth()->user()->name }}, dit is je persoonlijke dashboard.</p>
    </div>
</div>
@endsection