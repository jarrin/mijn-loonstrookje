@extends('layout.Layout')

@section('title', 'Documenten - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="employer-page-title">Documenten van {{ $employee->name ?? 'Alle Medewerkers' }}</h1>
    
    @if(session('success'))
        <div class="employer-alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="employer-alert-error">
            {{ session('error') }}
        </div>
    @endif

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
    
    @if(isset($documents))
    <table>
        <thead>
            <tr>
                @if(!isset($employee))
                <th>Medewerker</th>
                @endif
                <th>Document Naam</th>
                <th>Type</th>
                <th>Periode</th>
                <th>Versie</th>
                <th>Grootte</th>
                <th>Upload Datum</th>
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
                <td>
                    @php
                        $parentId = $document->parent_document_id ?? $document->id;
                        $allVersions = \App\Models\Document::where(function($q) use ($parentId) {
                            $q->where('parent_document_id', $parentId)
                              ->orWhere('id', $parentId);
                        })->orderBy('version')->get();
                        $maxVersion = $allVersions->max('version');
                        $isLatest = $document->version == $maxVersion;
                        $isOriginal = $document->version == 1.0 && !$document->parent_document_id;
                        $versionCount = $allVersions->count();
                    @endphp
                    
                    <div class="document-version-container">
                        <span class="document-version-number">v{{ number_format($document->version, 1) }}</span>
                        
                        @if($isOriginal)
                            <span class="document-version-badge document-version-original">
                                #{{ $document->id }}
                            </span>
                        @else
                            <span class="document-version-badge document-version-derived">
                                van #{{ $parentId }}
                            </span>
                        @endif
                        
                        @if($isLatest && $document->version > 1.0)
                            <span class="document-version-badge document-version-latest">
                                NIEUWSTE
                            </span>
                        @endif
                    </div>
                </td>
                <td>{{ $document->formatted_size }}</td>
                <td class="icon-cell">
                    <div class="document-actions-container">
                        <a href="{{ route('documents.view', $document->id) }}" 
                           target="_blank" 
                           title="Bekijken"
                           class="document-action-view">
                            👁️
                        </a>
                        <a href="{{ route('documents.download', $document->id) }}" 
                           title="Downloaden"
                           class="document-action-download">
                            ⬇️
                        </a>
                        <a href="{{ route('documents.edit', $document->id) }}" 
                           title="Bijwerken (nieuwe versie)"
                           class="document-action-edit">
                            ✏️
                        </a>
                        <form action="{{ route('documents.destroy', $document->id) }}" 
                              method="POST" 
                              style="display: inline;"
                              onsubmit="return confirm('Weet je zeker dat je dit document wilt verwijderen?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    title="Verwijderen"
                                    class="document-action-delete">
                                {!! file_get_contents(resource_path('assets/icons/trashbin.svg')) !!}
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ isset($employee) ? '7' : '8' }}" style="text-align: center;">Geen documenten gevonden</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @else
    <p>Hier komen alle documenten te staan.</p>
    @endif
    
    <div class="employer-footer-nav">
        @if(isset($employee))
            <a href="{{ route('documents.upload', $employee->id) }}" class="employer-footer-link">Document Uploaden</a>
            <span class="employer-footer-separator">|</span>
            <a href="{{ $backUrl ?? route('employer.employees') }}" class="employer-footer-link">Terug naar Medewerkers</a>
        @else
            <a href="{{ route('documents.upload') }}" class="employer-footer-link">Document Uploaden</a>
            <span class="employer-footer-separator">|</span>
            <a href="{{ route('employer.employees') }}" class="employer-footer-link">Medewerkers</a>
        @endif
        <span class="employer-footer-separator">|</span>
        <a href="{{ isset($employee) ? route('documents.deleted', ['employee' => $employee->id]) : route('documents.deleted') }}" class="employer-footer-link">Verwijderde Documenten</a>
        <span class="employer-footer-separator">|</span>
        <a href="{{ route('employer.dashboard') }}" class="employer-footer-link">Dashboard</a>
    </div>
</section>
@endsection
