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
                        $badgeClass = match($log->action) {
                            'login' => 'superadmin-log-badge-login',
                            'document_uploaded' => 'superadmin-log-badge-upload',
                            'document_revised' => 'superadmin-log-badge-revised',
                            'document_deleted' => 'superadmin-log-badge-deleted',
                            'document_restored' => 'superadmin-log-badge-restored',
                            'employee_created' => 'superadmin-log-badge-created',
                            'admin_office_added' => 'superadmin-log-badge-added',
                            default => 'superadmin-log-badge-default'
                        };
                    @endphp
                    <span class="superadmin-log-badge {{ $badgeClass }}">
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
