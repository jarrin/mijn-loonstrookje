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
                        
                        @if($isOriginal)
                            <span class="employee-version-badge original" style="background: var(--primary-color);">
                                #{{ $document->id }}
                            </span>
                        @else
                            <span class="employee-version-badge parent">
                                van #{{ $parentId }}
                            </span>
                        @endif
                        
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
                           class="employee-action-link"
                           style="color: var(--primary-color);">
                            👁️
                        </a>
                        <a href="{{ route('documents.download', $document->id) }}" 
                           title="Downloaden"
                           class="employee-action-link"
                           style="color: #10B981;">
                            ⬇️
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