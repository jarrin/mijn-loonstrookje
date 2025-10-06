@extends('layout.Layout')

@section('title', 'Super Admin Dashboard - Mijn Loonstrookje')

@section('content')
<div class="row">
    <div class="col-12">
        <h1>Super Admin Dashboard</h1>
        <p class="lead">Welkom {{ auth()->user()->name }}, je hebt volledige toegang tot het systeem.</p>
    </div>
</div>
@endsection