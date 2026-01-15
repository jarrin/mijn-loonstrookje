@extends('layout.Layout')

@section('title', 'Medewerker Dashboard - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="text-2xl mb-4">Medewerker Dashboard</h1>
    <p>Welkom {{ auth()->user()->name }}, dit is je persoonlijke dashboard.</p>
</section>
@endsection