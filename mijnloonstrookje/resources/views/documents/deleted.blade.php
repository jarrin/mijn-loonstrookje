@extends('layout.Layout')

@section('title', 'Verwijderde Documenten - Mijn Loonstrookje')

@section('content')
<section>
    @if(isset($employee))
        <div class="employer-back-link">
            <a href="{{ route('employer.employee.documents', $employee->id) }}" style="color: var(--primary-color);">
                ← Terug naar {{ $employee->name }}
            </a>
        </div>
    @elseif(auth()->user()->role === 'administration_office')
        <div class="employer-back-link">
            <a href="{{ route('administration.dashboard') }}" style="color: var(--primary-color);">
                ← Terug naar Dashboard
            </a>
        </div>
    @else
        <div class="employer-back-link">
            <a href="{{ route('employer.documents') }}" style="color: var(--primary-color);">
                ← Terug naar Documenten
            </a>
        </div>
    @endif
    
    <h1 class="employer-page-title">
        @if(isset($employee))
            Verwijderde Documenten van {{ $employee->name }}
        @elseif(isset($company))
            Verwijderde Documenten van {{ $company->name }}
        @else
            Verwijderde Documenten
        @endif
    </h1>

    @include('components.TableFilterBar', [
        'filters' => [
            [
                'label' => 'Type document',
                'options' => ['Loonstrook', 'Jaaroverzicht', 'Overig']
            ],
            [
                'label' => 'Periode',
                'options' => ['Deze maand', 'Vorige maand', 'Dit kwartaal', 'Dit jaar']
            ],
            [
                'label' => 'Sorteer op',
                'options' => ['Nieuwste eerst', 'Oudste eerst', 'Naam A-Z', 'Naam Z-A']
            ]
        ]
    ])
    
    <table>
        <thead>
            <tr>
                @if(!isset($employee))
                <th>Medewerker</th>
                @endif
                <th>Document Naam</th>
                <th>Type</th>
                <th>Periode</th>
                <th>Verwijderd op</th>
                <th>Verwijderd door</th>
                <th class="icon-cell">Acties</th>
            </tr>
        </thead>
        <tbody>
            @forelse($documents as $document)
            <tr>
                @if(!isset($employee))
                <td>{{ $document->employee->name ?? 'N/A' }}</td>
                @endif
                <td>{{ $document->display_name }}</td>
                <td>
                    @switch($document->type)
                        @case('payslip')
                            Loonstrook
                            @break
                        @case('annual_statement')
                            Jaaroverzicht
                            @break
                        @case('other')
                            Overig
                            @break
                        @default
                            {{ ucfirst($document->type) }}
                    @endswitch
                </td>
                <td>
                    @if($document->month)
                        {{ ['', 'Jan', 'Feb', 'Mrt', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'][$document->month] }} {{ $document->year }}
                    @elseif($document->week)
                        Week {{ $document->week }}, {{ $document->year }}
                    @else
                        {{ $document->year }}
                    @endif
                </td>
                <td>{{ $document->deleted_at ? $document->deleted_at->format('d-m-Y H:i') : 'N/A' }}</td>
                <td>{{ $document->uploader->name ?? 'N/A' }}</td>
                <td class="icon-cell-left">
                    <form action="{{ route('documents.restore', $document->id) }}" 
                          method="POST" 
                          class="document-action-form"
                          onsubmit="return confirm('Weet je zeker dat je dit document wilt herstellen?');">
                        @csrf
                        <button type="submit" 
                                title="Herstellen"
                                class="document-action-btn"
                                style="color: var(--primary-color);">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-undo2-icon lucide-undo-2"><path d="M9 14 4 9l5-5"/><path d="M4 9h10.5a5.5 5.5 0 0 1 5.5 5.5a5.5 5.5 0 0 1-5.5 5.5H11"/></svg>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ isset($employee) ? '6' : '7' }}" style="text-align: center; padding: 2rem; color: #6B7280;">
                    Geen verwijderde documenten gevonden
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</section>
@endsection
