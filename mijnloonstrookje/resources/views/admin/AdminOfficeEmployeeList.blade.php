@extends('layout.Layout')

@section('title', 'Medewerkers - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="text-2xl mb-4">Alle Medewerkers</h1>
    <p class="text-gray-600 mb-6">Overzicht van alle medewerkers van bedrijven waartoe je toegang hebt</p>
    
    @if($employees->isEmpty())
        <div class="bg-yellow-50 border border-yellow-200 rounded p-4 text-center mb-4">
            <p>Er zijn nog geen medewerkers beschikbaar.</p>
        </div>
    @else
        <div class="bg-white shadow overflow-x-auto mb-4">
            <table class="min-w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Naam</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Bedrijf</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Acties</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $employee)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $employee->name }}</td>
                            <td class="px-4 py-2">{{ $employee->email }}</td>
                            <td class="px-4 py-2">{{ $employee->company->name ?? 'N/A' }}</td>
                            <td class="px-4 py-2">
                                <span class="text-xs px-2 py-1 rounded bg-green-100 text-green-800">
                                    Actief
                                </span>
                            </td>
                            <td class="icon-cell">
                                <a href="{{ route('employer.employee.documents', $employee->id) }}" 
                                   style="color: var(--primary-color); cursor: pointer;">
                                    📄 Documenten
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
    
    <div class="mt-6">
        <a href="{{ route('administration.dashboard') }}" 
           style="color: var(--primary-color); cursor: pointer;">← Terug naar Dashboard</a>
    </div>
</section>
@endsection
