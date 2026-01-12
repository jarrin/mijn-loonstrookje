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
                    v{{ number_format($document->version, 1) }}
                    @if($document->version == 1.0 && !$document->parent_document_id)
                        <span style="background: #3B82F6; color: white; padding: 2px 6px; border-radius: 4px; font-size: 0.75rem; margin-left: 4px;">ORIGINEEL</span>
                    @endif
                    @php
                        $parentId = $document->parent_document_id ?? $document->id;
                        $maxVersion = \App\Models\Document::where('parent_document_id', $parentId)
                                     ->orWhere('id', $parentId)
                                     ->max('version');
                        $isLatest = $document->version == $maxVersion;
                    @endphp
                    @if($isLatest && $document->version > 1.0)
                        <span style="background: #10B981; color: white; padding: 2px 6px; border-radius: 4px; font-size: 0.75rem; margin-left: 4px;">NIEUWSTE</span>
                    @endif
                </td>
                <td>{{ $document->formatted_size }}</td>
                <td>{{ $document->created_at->format('d-m-Y') }}</td>
                <td>{{ $document->uploader->name ?? 'N/A' }}</td>
                <td class="icon-cell">
                    <div style="display: flex; gap: 8px; justify-content: center;">
                        <a href="{{ route('documents.view', $document->id) }}" 
                           target="_blank" 
                           title="Bekijken"
                           style="cursor: pointer; color: #3B82F6;">
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
        <a href="{{ route('employee.dashboard') }}">Terug naar Dashboard</a>
    </div>
</section>
@endsection
