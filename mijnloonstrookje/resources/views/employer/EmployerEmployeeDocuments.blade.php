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
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-icon lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                        </a>
                        <a href="{{ route('documents.download', $document->id) }}" 
                           title="Downloaden"
                           class="document-action-download">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-download-icon lucide-download"><path d="M12 15V3"/><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5"/></svg>
                        </a>
                        <a href="{{ route('documents.edit', $document->id) }}" 
                           title="Bijwerken (nieuwe versie)"
                           class="document-action-edit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-pen-icon lucide-square-pen"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/></svg>
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
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2-icon lucide-trash-2"><path d="M10 11v6"/><path d="M14 11v6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
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
        @else
            <a href="{{ route('documents.upload') }}" class="employer-footer-link">Document Uploaden</a>
            <span class="employer-footer-separator">|</span>
        @endif
        <span class="employer-footer-separator">|</span>
        <a href="{{ isset($employee) ? route('documents.deleted', ['employee' => $employee->id]) : route('documents.deleted') }}" class="employer-footer-link">Verwijderde Documenten</a>
    </div>
</section>
@endsection
