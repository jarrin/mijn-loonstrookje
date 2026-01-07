@extends('layout.Layout')

@section('title', 'Medewerkers - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="text-2xl mb-4">Medewerkers Lijst</h1>
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif
    
    <table>
        <thead>
            <tr>
                <th>Naam</th>
                <th>Email</th>
                <th>Status</th>
                <th class="icon-cell">Acties</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees ?? [] as $employee)
            <tr style="cursor: pointer;" onclick="window.location='{{ route('employer.employee.documents', $employee->id) }}'">
                <td>{{ $employee->name }}</td>
                <td>{{ $employee->email }}</td>
                <td>Actief</td>
                <td class="icon-cell">{!! file_get_contents(resource_path('assets/icons/trashbin.svg')) !!}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center;">Geen medewerkers gevonden</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="mt-6 space-x-4">
        <button onclick="window.location='{{ route('employer.invite.employee') }}}'" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Medewerker Toevoegen</button>
        <a href="{{ route('employer.dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Terug naar Dashboard</a>
        <a href="{{ route('employer.documents') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Alle Documenten</a>
        <a href="{{ route('documents.deleted') }}" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Verwijderde Documenten</a>
    </div>
</section>
@endsection
