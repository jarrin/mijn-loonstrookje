@extends('layout.Layout')

@section('title', 'Medewerkers - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="text-2xl mb-4">Medewerkers Lijst</h1>
    
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
            @forelse($employees as $employee)
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
        <a href="{{ route('employer.dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Terug naar Dashboard</a>
        <a href="{{ route('employer.documents') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Alle Documenten</a>
    </div>
</section>
@endsection
