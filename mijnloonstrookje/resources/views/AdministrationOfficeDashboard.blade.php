@extends('layout.Layout')

@section('title', 'Administratiekantoor Dashboard - Mijn Loonstrookje')

@section('content')
<div class="row">
    <div class="col-12">
        <h1>Administratiekantoor Dashboard</h1>
        <p class="lead">Welkom {{ auth()->user()->name }}, beheer hier meerdere werkgevers en hun administratie.</p>
    </div>
</div>
@endsection