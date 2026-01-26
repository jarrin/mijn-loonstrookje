@extends('layout.Layout')

@section('title', 'Medewerkers - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="employee-list-title">Alle Medewerkers</h1>
    <p class="employee-list-subtitle">Overzicht van alle medewerkers van bedrijven waartoe je toegang hebt</p>
    
    @if($employees->isEmpty())
        <div class="employee-list-no-data">
            <p>Er zijn nog geen medewerkers beschikbaar.</p>
        </div>
    @else
        <div class="employee-list-table-container">
            <table class="employee-list-table">
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
                                <span class="employee-list-status-badge active">
                                    Actief
                                </span>
                            </td>
                            <td class="icon-cell">
                                <a href="{{ route('employer.employee.documents', $employee->id) }}" 
                                   class="employee-list-action-link"
                                   style="color: var(--primary-color);">
                                    📄 Documenten
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
    
    <div class="employee-list-footer">
        <a href="{{ route('administration.dashboard') }}" 
           class="employee-list-footer-link"
           style="color: var(--primary-color);">← Terug naar Dashboard</a>
    </div>
</section>
@endsection
