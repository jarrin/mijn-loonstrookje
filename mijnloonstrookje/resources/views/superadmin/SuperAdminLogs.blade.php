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
                            'login' => ['bg' => 'rgba(4, 211, 0, 0.3)', 'text' => '#00BC0D'],
                            'document_uploaded' => ['bg' => 'rgba(0, 149, 255, 0.3)', 'text' => '#0095FF'],
                            'document_revised' => ['bg' => 'rgba(255, 132, 0, 0.3)', 'text' => '#FF8400'],
                            'document_deleted' => ['bg' => 'rgba(255, 22, 22, 0.3)', 'text' => '#FF1616'],
                            'document_restored' => ['bg' => 'rgba(145, 0, 236, 0.3)', 'text' => '#9100EC'],
                            'employee_created' => ['bg' => 'rgba(165, 243, 252, 0.3)', 'text' => '#0891B2'],
                            'admin_office_added' => ['bg' => 'rgba(251, 207, 232, 0.3)', 'text' => '#DB2777'],
                            default => ['bg' => 'rgba(229, 231, 235, 0.3)', 'text' => '#4B5563']
                        };
                    @endphp
                    <span class="superadmin-log-badge" style="background-color: {{ $colors['bg'] }}; color: {{ $colors['text'] }};">
                        {{ ucfirst(str_replace('_', ' ', $log->action)) }}
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
    
    <!-- Pagination -->
    <div class="superadmin-pagination-wrapper">
        {{ $logs->links() }}
    </div>
</section>
@endsection
