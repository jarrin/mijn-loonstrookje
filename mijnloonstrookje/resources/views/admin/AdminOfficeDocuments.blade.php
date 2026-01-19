@extends('layout.Layout')

@section('title', 'Documenten - Mijn Loonstrookje')

@section('content')
<section>
    @if(isset($company))
        <div class="mb-4">
            <a href="{{ route('administration.company.show', $company->id) }}" class="hover:underline" style="color: var(--primary-color);">
                ← Terug naar {{ $company->name }}
            </a>
        </div>
        <h1 class="text-2xl mb-4">Documenten - {{ $company->name }}</h1>
    @else
        <h1 class="text-2xl mb-4">Alle Documenten</h1>
    @endif
    
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
    
    @if($documents->isEmpty())
        <div class="bg-yellow-50 border border-yellow-200 rounded p-4 text-center mb-4">
            <p>Er zijn nog geen documenten beschikbaar.</p>
        </div>
    @else
        <div class="bg-white shadow overflow-x-auto mb-4">
            <table class="min-w-full">
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
                            
                            <div class="flex items-center gap-1">
                                <span class="font-medium">v{{ number_format($document->version, 1) }}</span>
                                
                                @if($isOriginal)
                                    <span class="text-xs px-2 py-1 rounded" style="background-color: var(--primary-color); color: white;">
                                        #{{ $document->id }}
                                    </span>
                                @else
                                    <span class="text-xs px-2 py-1 rounded bg-gray-400 text-white">
                                        van #{{ $parentId }}
                                    </span>
                                @endif
                                
                                @if($isLatest && $document->version > 1.0)
                                    <span class="text-xs px-2 py-1 rounded" style="background-color: #10B981; color: white;">
                                        NIEUWSTE
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-2">{{ $document->created_at->format('d-m-Y') }}</td>
                        <td class="icon-cell">
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <a href="{{ route('documents.view', $document->id) }}" 
                                   target="_blank" 
                                   title="Bekijken"
                                   style="color: var(--primary-color); cursor: pointer;">
                                    👁️
                                </a>
                                <a href="{{ route('documents.download', $document->id) }}" 
                                   title="Downloaden"
                                   style="color: #10B981; cursor: pointer;">
                                    ⬇️
                                </a>
                                <a href="{{ route('documents.edit', $document->id) }}" 
                                   title="Bijwerken"
                                   style="color: #F59E0B; cursor: pointer;">
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
                                            style="background: none; border: none; color: #EF4444; cursor: pointer; padding: 0; font-size: inherit;">
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
    
    <div class="mt-6 flex gap-4">
        <a href="{{ isset($company) ? route('documents.upload') . '?company=' . $company->id : route('documents.upload') }}" class="text-white font-bold py-2 px-4 rounded hover:opacity-90" style="background-color: var(--primary-color);">
            Document Uploaden
        </a>
        <a href="{{ route('documents.deleted') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
            Verwijderde Documenten
        </a>
    </div>
    
    <div class="mt-6">
        @if(isset($company))
            <a href="{{ route('administration.company.show', $company->id) }}" class="hover:underline" style="color: var(--primary-color);">← Terug naar {{ $company->name }}</a>
        @else
            <a href="{{ route('administration.dashboard') }}" class="hover:underline" style="color: var(--primary-color);">← Terug naar Dashboard</a>
        @endif
    </div>
</section>
@endsection
