@extends('layout.Layout')

@section('title', 'Medewerker Dashboard - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="text-2xl mb-4">Medewerker Dashboard</h1>
    <p>Welkom {{ auth()->user()->name }}, dit is je persoonlijke dashboard.</p>
    
    <div class="mt-6 space-x-4">
        <a href="{{ route('employee.documents') }}">Mijn Documenten</a>
        <a href="{{ route('home') }}">Home</a>
    </div>
</section>
@endsection