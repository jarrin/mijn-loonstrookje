@extends('layout.Layout')

@section('title', 'Medewerkers - ' . $company->name . ' - Mijn Loonstrookje')

@section('content')
<section>
    <div class="employees-header">
        <a href="{{ route('administration.dashboard') }}" class="employees-back-link" style="color: var(--primary-color);">
            ← Terug naar Dashboard
        </a>
        
        <h1 class="employees-title">Medewerkers - {{ $company->name }}</h1>
        <p class="employees-subtitle">Overzicht van alle medewerkers van dit bedrijf</p>
    </div>

    @if($employees->isEmpty())
        <div class="employees-no-data">
            <p>Dit bedrijf heeft nog geen medewerkers.</p>
        </div>
    @else
        @include('components.TableFilterBar', [
            'filters' => [
                [
                    'label' => 'Status',
                    'options' => ['Actief', 'Inactief']
                ],
                [
                    'label' => 'Sorteer op',
                    'options' => ['Naam A-Z', 'Naam Z-A']
                ]
            ]
        ])
        
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
                            <td class="px-4 py-2" style="cursor: pointer;" onclick="window.location='{{ route('employer.employee.documents', $employee->id) }}'">{{ $employee->name }}</td>
                            <td class="px-4 py-2" style="cursor: pointer;" onclick="window.location='{{ route('employer.employee.documents', $employee->id) }}'">{{ $employee->email }}</td>
                            <td class="px-4 py-2" style="cursor: pointer;" onclick="window.location='{{ route('employer.employee.documents', $employee->id) }}'">
                                <span style="padding: 0.3rem 0.8rem; border-radius: 50px; font-size: 0.75rem; font-weight: 600; display: inline-block; background-color: rgba(4, 211, 0, 0.3); color: #00BC0D;">
                                    Actief
                                </span>
                            </td>
                            <td class="icon-cell-left">
                                <a href="{{ route('employer.employee.documents', $employee->id) }}" 
                                   class="employee-action-link"
                                   style="color: var(--primary-color);">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-icon lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
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
