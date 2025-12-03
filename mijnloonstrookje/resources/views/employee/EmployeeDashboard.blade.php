@extends('layout.Layout')

@section('title', 'Medewerker Dashboard - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="text-2xl mb-4">Medewerker Dashboard testoe</h1>
    <p>Welkom {{ auth()->user()->name }}, dit is je persoonlijke dashboard.</p>
    
    <div class="mt-6 space-x-4">
        <a href="{{ route('home') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Home</a>
    </div>
</section>
@endsection