@extends('layout.Layout')

@section('title', 'Mijn Documenten - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="employee-page-title">Mijn Documenten</h1>
    <p class="employee-welcome-text">Welkom {{ auth()->user()->name }}, hier vind je al je documenten.</p>
    
    @if(session('success'))
        <div class="employee-alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="employee-alert-error">
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
    
    <table>
        <thead>
            <tr>
                <th>Document Naam</th>
                <th>Type</th>
                <th>Periode</th>
                <th>Versie</th>
                <th>Grootte</th>
                <th>Upload Datum</th>
                <th>Geüpload door</th>
                <th class="icon-cell">Acties</th>
            </tr>
        </thead>
        <tbody>
            @forelse($documents as $document)
            <tr>
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
                    
                    <div class="employee-version-container">
                        <span class="employee-version-number">v{{ number_format($document->version, 1) }}</span>
                        
                        @if($isLatest && $document->version > 1.0)
                            <span class="employee-version-badge latest">
                                NIEUWSTE
                            </span>
                        @endif
                    </div>
                </td>
                <td>{{ $document->formatted_size }}</td>
                <td>{{ $document->created_at->format('d-m-Y') }}</td>
                <td>{{ $document->uploader->name ?? 'N/A' }}</td>
                <td class="icon-cell">
                    <div class="employee-actions-container">
                        <a href="{{ route('documents.view', $document->id) }}" 
                           target="_blank" 
                           title="Bekijken"
                            class="document-action-view"
                           style="color: var(--primary-color);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-icon lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                        </a>
                        <a href="{{ route('documents.download', $document->id) }}" 
                           title="Downloaden"
                           class="document-action-download"
                           style="color: var(--primary-color);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 15V3"/><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5"/></svg>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center;">Geen documenten gevonden</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</section>
@endsection