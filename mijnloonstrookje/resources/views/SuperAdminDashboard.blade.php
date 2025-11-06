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
      
    
    <div class="mt-6 space-x-4">
        <a href="{{ route('superadmin.subscriptions') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Abonnementen</a>
        <a href="{{ route('superadmin.logs') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Logs</a>
        <a href="{{ route('superadmin.facturation') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Facturatie</a>
    </div>
</section>
@endsection