@extends('layout.Layout')

@section('title', 'Super Admin Dashboard - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="text-2xl mb-4">Super Admin Dashboard</h1>
    <p>Welkom {{ auth()->user()->name }}, je hebt volledige toegang tot het systeem.</p>
</section>
@endsection