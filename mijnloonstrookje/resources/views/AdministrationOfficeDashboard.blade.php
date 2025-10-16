@extends('layout.Layout')

@section('title', 'Administratiekantoor Dashboard - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="text-2xl mb-4">Administratiekantoor Dashboard</h1>
    <p>Welkom {{ auth()->user()->name }}, beheer hier meerdere werkgevers en hun administratie.</p>
</section>
@endsection