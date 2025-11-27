@extends('layout.Layout')

@section('title', 'Documenten - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="text-2xl mb-4">Documenten van {{ $employee->name ?? 'Alle Medewerkers' }}</h1>
    
    @if(isset($documents))
    <table>
        <thead>
            <tr>
                <th>Document Naam</th>
                <th>Type</th>
                <th>Datum</th>
                <th class="icon-cell">Acties</th>
            </tr>
        </thead>
        <tbody>
            @forelse($documents as $document)
            <tr>
                <td>{{ $document->name }}</td>
                <td>{{ $document->type }}</td>
                <td>{{ $document->date }}</td>
                <td class="icon-cell">{!! file_get_contents(resource_path('assets/icons/trashbin.svg')) !!}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center;">Geen documenten gevonden</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @else
    <p>Hier komen alle documenten te staan.</p>
    @endif
    
    <div class="mt-6 space-x-4">
        <a href="{{ route('employer.dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Terug naar Dashboard</a>
        <a href="{{ route('employer.employees') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Medewerkers</a>
    </div>
</section>
@endsection
