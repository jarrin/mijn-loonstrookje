@extends('layout.Layout')

@section('title', 'Documenten - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="text-2xl mb-4">Documenten van {{ $employee->name ?? 'Alle Medewerkers' }}</h1>
    
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
                        <a href="{{ route('documents.edit', $document->id) }}" 
                           title="Bijwerken (nieuwe versie)"
                           style="cursor: pointer; color: #F59E0B;">
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
                                    style="cursor: pointer; background: none; border: none; padding: 0; color: #EF4444;">
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
    
    <div class="mt-6 space-x-4">
        @if(isset($employee))
            <a href="{{ route('documents.upload', $employee->id) }}">Document Uploaden</a>
            <a href="{{ route('employer.employees') }}">Terug naar Medewerkers</a>
        @else
            <a href="{{ route('documents.upload') }}">Document Uploaden</a>
            <a href="{{ route('employer.employees') }}">Medewerkers</a>
        @endif
        <a href="{{ route('documents.deleted') }}">Verwijderde Documenten</a>
        <a href="{{ route('employer.dashboard') }}">Dashboard</a>
    </div>
</section>
@endsection
