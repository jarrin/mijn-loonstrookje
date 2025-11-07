@extends('layout.Layout')

@section('title', 'Super Admin Dashboard - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="text-2xl mb-4">Super Admin Dashboard</h1>
    <p>Welkom {{ auth()->user()->name }}, je hebt volledige toegang tot het systeem.</p>

    <table>
        <thead>
            <tr>
                <th>Gebruiker</th>
                <th>Bedrijf</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Status</th>
                <th class="icon-cell">Acties</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Jan Jansen</td>
                <td>DMG</td>
                <td>jansen@dmg.nl</td>
                <td>Admin</td>
                <td>Actief</td>
                <td class="icon-cell">{!! file_get_contents(resource_path('assets/icons/trashbin.svg')) !!}</td>
            </tr>
        </tbody>
    </table>
</section>
@endsection