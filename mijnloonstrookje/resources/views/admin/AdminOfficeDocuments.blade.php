@extends('layout.Layout')

@section('title', 'Documenten - Mijn Loonstrookje')

@section('content')
<section>
    @if(isset($company))
        <div class="documents-back-link">
            <a href="{{ route('administration.company.show', $company->id) }}" style="color: var(--primary-color);">
                ← Terug naar {{ $company->name }}
            </a>
        </div>
        <h1 class="documents-title">Documenten - {{ $company->name }}</h1>
    @else
        <h1 class="documents-title">Alle Documenten</h1>
    @endif
    
    @if(session('success'))
        <div class="documents-alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="documents-alert-error">
            {{ session('error') }}
        </div>
    @endif
    
    @if($documents->isEmpty())
        <div class="documents-no-data">
            <p>Er zijn nog geen documenten beschikbaar.</p>
        </div>
    @else
        <div class="documents-table-container">
            <table class="documents-table">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left">Bedrijf</th>
                        <th class="px-4 py-2 text-left">Medewerker</th>
                        <th class="px-4 py-2 text-left">Document Naam</th>
                        <th class="px-4 py-2 text-left">Type</th>
                        <th class="px-4 py-2 text-left">Periode</th>
                        <th class="px-4 py-2 text-left">Versie</th>
                        <th class="px-4 py-2 text-left">Upload Datum</th>
                        <th class="px-4 py-2 text-left">Acties</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documents as $document)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $document->company->name ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $document->employee->name ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $document->display_name }}</td>
                        <td class="px-4 py-2">
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
                        <td class="px-4 py-2">
                            @if($document->month)
                                {{ ['', 'Jan', 'Feb', 'Mrt', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Dec'][$document->month] }} {{ $document->year }}
                            @elseif($document->week)
                                Week {{ $document->week }}, {{ $document->year }}
                            @else
                                {{ $document->year }}
                            @endif
                        </td>
                        <td class="px-4 py-2">
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
                                    <span class="document-version-badge original" style="background-color: var(--primary-color);">
                                        #{{ $document->id }}
                                    </span>
                                @else
                                    <span class="document-version-badge parent">
                                        van #{{ $parentId }}
                                    </span>
                                @endif
                                
                                @if($isLatest && $document->version > 1.0)
                                    <span class="document-version-badge latest">
                                        NIEUWSTE
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-2">{{ $document->created_at->format('d-m-Y') }}</td>
                        <td class="icon-cell">
                            <div class="document-actions-container">
                                <a href="{{ route('documents.view', $document->id) }}" 
                                   target="_blank" 
                                   title="Bekijken"
                                   class="document-action-link"
                                   style="color: var(--primary-color);">
                                    👁️
                                </a>
                                <a href="{{ route('documents.download', $document->id) }}" 
                                   title="Downloaden"
                                   class="document-action-link"
                                   style="color: #10B981;">
                                    ⬇️
                                </a>
                                <a href="{{ route('documents.edit', $document->id) }}" 
                                   title="Bijwerken"
                                   class="document-action-link"
                                   style="color: #F59E0B;">
                                    ✏️
                                </a>
                                <form action="{{ route('documents.destroy', $document->id) }}" 
                                      method="POST" 
                                      class="document-action-form"
                                      onsubmit="return confirm('Weet je zeker dat je dit document wilt verwijderen?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            title="Verwijderen"
                                            class="document-action-btn"
                                            style="color: #EF4444;">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
    
    <div class="documents-button-group">
        <a href="{{ isset($company) ? route('documents.upload') . '?company=' . $company->id : route('documents.upload') }}" class="documents-primary-btn" style="background-color: var(--primary-color);">
            Document Uploaden
        </a>
        <a href="{{ isset($company) ? route('documents.deleted', ['company' => $company->id]) : route('documents.deleted') }}" class="documents-secondary-btn">
            Verwijderde Documenten
        </a>
    </div>
    
    <div class="documents-footer">
        @if(isset($company))
            <a href="{{ route('administration.company.show', $company->id) }}" class="documents-footer-link" style="color: var(--primary-color);">← Terug naar {{ $company->name }}</a>
        @else
            <a href="{{ route('administration.dashboard') }}" class="documents-footer-link" style="color: var(--primary-color);">← Terug naar Dashboard</a>
        @endif
    </div>
</section>
@endsection
