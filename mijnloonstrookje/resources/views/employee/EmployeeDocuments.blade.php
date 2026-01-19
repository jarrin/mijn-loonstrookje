@extends('layout.Layout')

@section('title', 'Mijn Documenten - Mijn Loonstrookje')

@section('content')
<section>
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
        <h1 class="text-2xl mb-4">Mijn Documenten</h1>
        <button onclick="openBulkDownloadModal()" 
                class="text-white px-4 py-2 rounded hover:opacity-90" 
                style="background-color: var(--primary-color);">
            📦 Bulk Download
        </button>
    </div>
    
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
</section>

<!-- Bulk Download Modal -->
<div id="bulkDownloadModal" style="display:none; position:fixed; inset:0; z-index:50; background:rgba(15, 23, 42, 0.4); align-items:center; justify-content:center;">
    <div style="background:white; border-radius:0.75rem; width:100%; max-width:500px; box-shadow:0 10px 40px rgba(15,23,42,0.25);">
        <div style="display:flex; justify-content:space-between; align-items:center; padding:1.5rem 1.75rem; border-bottom:1px solid #e5e7eb;">
            <h2 style="font-size:1.5rem; font-weight:600; color:#111827; margin:0;">Bulk Download</h2>
            <button type="button" onclick="closeBulkDownloadModal()" aria-label="Sluiten" style="background:transparent; border:none; font-size:1.25rem; cursor:pointer; color:#6b7280;">&times;</button>
        </div>

        <form id="bulkDownloadForm" action="{{ route('documents.bulk-download') }}" method="POST" style="padding:1.75rem;">
            @csrf

            <div style="margin-bottom:1rem;">
                <p style="color:#6b7280; font-size:0.875rem; line-height:1.5;">
                    Selecteer welke documenten je wilt downloaden als ZIP-bestand.
                </p>
            </div>

            <div style="display:flex; flex-direction:column; gap:1rem;">
                <div>
                    <label for="bulkType" style="display:block; font-size:0.875rem; font-weight:500; color:#374151; margin-bottom:0.5rem;">Document Type</label>
                    <select id="bulkType" name="type" style="width:100%; border-radius:0.75rem; border:1px solid #e5e7eb; padding:0.75rem 1rem; background:#f9fafb;">
                        <option value="all">Alle types</option>
                        <option value="payslip">Loonstroken</option>
                        <option value="annual_statement">Jaaroverzichten</option>
                        <option value="other">Overig</option>
                    </select>
                </div>

                <div>
                    <label for="bulkYear" style="display:block; font-size:0.875rem; font-weight:500; color:#374151; margin-bottom:0.5rem;">Jaar</label>
                    <select id="bulkYear" name="year" style="width:100%; border-radius:0.75rem; border:1px solid #e5e7eb; padding:0.75rem 1rem; background:#f9fafb;">
                        <option value="all">Alle jaren</option>
                        @php
                            $years = $documents->pluck('year')->unique()->sort()->reverse();
                        @endphp
                        @foreach($years as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:1rem; margin-top:1.75rem; border-top:1px solid #e5e7eb; padding-top:1.25rem;">
                <button type="button" onclick="closeBulkDownloadModal()" style="background:transparent; border:none; color:#6b7280; font-weight:500; padding:0.75rem 1.5rem; border-radius:9999px; cursor:pointer;">Annuleren</button>
                <button type="submit" style="background-color: var(--primary-color); color:white; border:none; font-weight:500; padding:0.75rem 1.75rem; border-radius:9999px; cursor:pointer; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">Download ZIP</button>
            </div>
        </form>
    </div>
</div>

<script>
function openBulkDownloadModal() {
    document.getElementById('bulkDownloadModal').style.display = 'flex';
}

function closeBulkDownloadModal() {
    document.getElementById('bulkDownloadModal').style.display = 'none';
    document.getElementById('bulkDownloadForm').reset();
}

// Close modal when clicking outside
document.getElementById('bulkDownloadModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeBulkDownloadModal();
    }
});
</script>
@endsection
