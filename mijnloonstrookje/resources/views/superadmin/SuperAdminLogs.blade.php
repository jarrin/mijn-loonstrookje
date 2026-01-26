@extends('layout.Layout')

@section('title', 'Logs - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="superadmin-page-title">Systeem Logs</h1>
    <p class="superadmin-page-subtitle">Bekijk en filter alle systeem activiteiten</p>

    @include('components.TableFilterBar', [
        'filters' => [
            [
                'label' => 'Type actie',
                'options' => ['Login', 'Document uploaded', 'Document revised', 'Document deleted', 'Document restored', 'Employee created', 'Admin office added']
            ],
            [
                'label' => 'Alle logs',
                'options' => ['Vandaag', 'Deze week', 'Deze maand', 'Dit jaar']
            ],
            [
                'label' => 'Sorteer op',
                'options' => ['Nieuwste eerst', 'Oudste eerst', 'Gebruiker', 'Bedrijf']
            ]
        ]
    ])
    
    <table>
        <thead>
            <tr>
                <th>Timestamp</th>
                <th>Gebruiker</th>
                <th>Bedrijf</th>
                <th>Actie</th>
                <th>Beschrijving</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
            <tr>
                <td>{{ $log->created_at->format('d-m-Y H:i:s') }}</td>
                <td>{{ $log->user ? $log->user->name : 'N/A' }}</td>
                <td>
                    @if($log->company)
                        {{ $log->company->name }}
                    @elseif($log->user && $log->user->role === 'administration_office')
                        @php
                            $companies = $log->user->companies;
                            $count = $companies->count();
                            $allCompanies = $companies->pluck('name')->join(', ');
                        @endphp
                        @if($count === 1)
                            {{ $companies->first()->name }}
                        @elseif($count > 1)
                            <span title="{{ $allCompanies }}" style="cursor: help; border-bottom: 1px dotted #666;">
                                {{ $count }} Bedrijven
                            </span>
                        @else
                            N/A
                        @endif
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    @php
                        $colors = match($log->action) {
                            'login', 'inloggen' => ['bg' => 'rgba(4, 211, 0, 0.3)', 'text' => '#00BC0D'],
                            'document_uploaded', 'document_geupload' => ['bg' => 'rgba(0, 149, 255, 0.3)', 'text' => '#0095FF'],
                            'document_revised', 'document_herzien' => ['bg' => 'rgba(255, 132, 0, 0.3)', 'text' => '#FF8400'],
                            'document_deleted', 'document_verwijderd' => ['bg' => 'rgba(255, 22, 22, 0.3)', 'text' => '#FF1616'],
                            'document_restored', 'document_hersteld' => ['bg' => 'rgba(145, 0, 236, 0.3)', 'text' => '#9100EC'],
                            'employee_created', 'medewerker_aangemaakt' => ['bg' => 'rgba(165, 243, 252, 0.3)', 'text' => '#0891B2'],
                            'admin_office_added', 'admin_bureau_toegevoegd' => ['bg' => 'rgba(251, 207, 232, 0.3)', 'text' => '#DB2777'],
                            default => ['bg' => 'rgba(229, 231, 235, 0.3)', 'text' => '#4B5563']
                        };
                        
                        $actionTranslations = [
                            'login' => 'Inloggen',
                            'inloggen' => 'Inloggen',
                            'document_uploaded' => 'Document geüpload',
                            'document_geupload' => 'Document geüpload',
                            'document_revised' => 'Document herzien',
                            'document_herzien' => 'Document herzien',
                            'document_deleted' => 'Document verwijderd',
                            'document_verwijderd' => 'Document verwijderd',
                            'document_restored' => 'Document hersteld',
                            'document_hersteld' => 'Document hersteld',
                            'employee_created' => 'Medewerker aangemaakt',
                            'medewerker_aangemaakt' => 'Medewerker aangemaakt',
                            'admin_office_added' => 'Admin bureau toegevoegd',
                            'admin_bureau_toegevoegd' => 'Admin bureau toegevoegd',
                        ];
                        
                        $displayAction = $actionTranslations[$log->action] ?? ucfirst(str_replace('_', ' ', $log->action));
                    @endphp
                    <span class="superadmin-log-badge" style="background-color: {{ $colors['bg'] }}; color: {{ $colors['text'] }};">
                        {{ $displayAction }}
                    </span>
                </td>
                <td>{{ $log->description ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">Geen logs gevonden</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    @if($logs->hasPages())
    <div class="superadmin-pagination-wrapper">
        {{ $logs->links('vendor.pagination.generic') }}
    </div>
    @endif
</section>
@endsection
