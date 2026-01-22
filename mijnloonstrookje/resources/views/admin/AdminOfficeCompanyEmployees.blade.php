@extends('layout.Layout')

@section('title', 'Medewerkers - ' . $company->name . ' - Mijn Loonstrookje')

@section('content')
<section>
    <div class="employees-header">
        <a href="{{ route('administration.company.show', $company->id) }}" class="employees-back-link" style="color: var(--primary-color);">
            ← Terug naar {{ $company->name }}
        </a>
        
        <h1 class="employees-title">Medewerkers - {{ $company->name }}</h1>
        <p class="employees-subtitle">Overzicht van alle medewerkers van dit bedrijf</p>
    </div>

    @if($employees->isEmpty())
        <div class="employees-no-data">
            <p>Dit bedrijf heeft nog geen medewerkers.</p>
        </div>
    @else
        <div class="employees-table-container">
            <table class="employees-table">
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
                                <span class="employee-status-badge active">
                                    Actief
                                </span>
                            </td>
                            <td class="icon-cell">
                                <a href="{{ route('employer.employee.documents', $employee->id) }}" 
                                   class="employee-action-link"
                                   style="color: var(--primary-color);">
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
