@extends('layout.Layout')

@section('title', 'Medewerkers - ' . $company->name . ' - Mijn Loonstrookje')

@section('content')
<section>
    <div class="mb-6">
        <a href="{{ route('administration.company.show', $company->id) }}" class="hover:underline mb-4 inline-block" style="color: var(--primary-color);">
            ← Terug naar {{ $company->name }}
        </a>
        
        <h1 class="text-2xl font-bold mb-2">Medewerkers - {{ $company->name }}</h1>
        <p class="text-gray-600">Overzicht van alle medewerkers van dit bedrijf</p>
    </div>

    @if($employees->isEmpty())
        <div class="bg-yellow-50 border border-yellow-200 rounded p-4 text-center">
            <p>Dit bedrijf heeft nog geen medewerkers.</p>
        </div>
    @else
        <div class="bg-white shadow overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Naam</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Acties</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $employee)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-4 py-2">{{ $employee->name }}</td>
                            <td class="px-4 py-2">{{ $employee->email }}</td>
                            <td class="px-4 py-2">
                                <span class="text-xs px-2 py-1 rounded bg-green-100 text-green-800">
                                    Actief
                                </span>
                            </td>
                            <td class="icon-cell">
                                <a href="{{ route('employer.employee.documents', $employee->id) }}" 
                                   style="color: var(--primary-color); cursor: pointer;">
                                    📄 Bekijk Documenten
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</section>
@endsection
