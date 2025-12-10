@extends('layout.Layout')

@section('title', 'Super Admin Dashboard - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="text-2xl mb-4">Super Admin Dashboard</h1>
    <p>Welkom {{ auth()->user()->name }}, je hebt volledige toegang tot het systeem.</p>

    @include('components.TableFilterBar', [
        'filters' => [
            [
                'label' => 'Type gebruiker',
                'options' => ['Bedrijf', 'Werknemer', 'Administratie bureau']
            ],
            [
                'label' => 'Alle gebruikers',
                'options' => ['Actief', 'Inactief', 'In afwachting']
            ],
            [
                'label' => 'Alle gebruikers',
                'options' => ['Alfabetisch', 'Datum', 'Status']
            ]
        ]
    ])

    <table id="superadmin-table">
        <thead>
            <tr>
                <th>Gebruiker</th>
                <th>Bedrijf</th>
                <th>Email</th>
                <th>Type</th>
                <th>Status</th>
                <th class="icon-cell">Acties</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->company->name ?? '-' }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td><span class="status-label">Ready</span></td>
                    <td class="icon-cell">
                        <form action="{{ route('superadmin.users.destroy', $user) }}" method="POST" style="display:inline" onsubmit="return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?');">
                            @csrf
                            @method('DELETE')
                            <button id="delete-table-button" type="submit" title="Verwijder gebruiker" class="text-red-600">
                                {!! file_get_contents(resource_path('assets/icons/trashbin.svg')) !!}
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Geen gebruikers gevonden.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</section>
@endsection