@extends('layout.Layout')

@section('title', 'Mijn Documenten - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="text-2xl mb-4">Mijn Documenten</h1>
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
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
                    
                    <div style="display: flex; align-items: center; gap: 4px;">
                        <span style="font-weight: 500;">v{{ number_format($document->version, 1) }}</span>
                        
                        @if($isOriginal)
                            <span style="background: var(--primary-color); color: white; padding: 2px 6px; border-radius: 4px; font-size: 0.75rem;">
                                #{{ $document->id }}
                            </span>
                        @else
                            <span style="background: #9CA3AF; color: white; padding: 2px 6px; border-radius: 4px; font-size: 0.75rem;">
                                van #{{ $parentId }}
                            </span>
                        @endif
                        
                        @if($isLatest && $document->version > 1.0)
                            <span style="background: #10B981; color: white; padding: 2px 6px; border-radius: 4px; font-size: 0.75rem;">
                                NIEUWSTE
                            </span>
                        @endif
                    </div>
                </td>
                <td>{{ $document->formatted_size }}</td>
                <td>{{ $document->created_at->format('d-m-Y') }}</td>
                <td>{{ $document->uploader->name ?? 'N/A' }}</td>
                <td class="icon-cell">
                    <div style="display: flex; gap: 8px; justify-content: center;">
                        <a href="{{ route('documents.view', $document->id) }}" 
                           target="_blank" 
                           title="Bekijken"
                           style="cursor: pointer; color: var(--primary-color);">
                            👁️
                        </a>
                        <a href="{{ route('documents.download', $document->id) }}" 
                           title="Downloaden"
                           style="cursor: pointer; color: #10B981;">
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
    
    <div class="mt-6 space-x-4">
        <a href="{{ route('employee.dashboard') }}" style="color: var(--primary-color); cursor: pointer;">← Terug naar Dashboard</a>
    </div>
</section>
@endsection
