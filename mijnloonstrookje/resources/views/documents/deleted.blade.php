@extends('layout.Layout')

@section('title', 'Verwijderde Documenten - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="documents-page-title">
        @if(isset($employee))
            Verwijderde Documenten van {{ $employee->name }}
        @elseif(isset($company))
            Verwijderde Documenten van {{ $company->name }}
        @else
            Verwijderde Documenten
        @endif
    </h1>
    
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
    
    <div class="documents-table-container">
        <table class="documents-table">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">Medewerker</th>
                    <th class="px-4 py-2 text-left">Document Naam</th>
                    <th class="px-4 py-2 text-left">Type</th>
                    <th class="px-4 py-2 text-left">Periode</th>
                    <th class="px-4 py-2 text-left">Verwijderd op</th>
                    <th class="px-4 py-2 text-left">Verwijderd door</th>
                    <th class="px-4 py-2 text-left">Acties</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documents as $document)
                <tr class="border-t hover:bg-gray-50">
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
                    <td class="px-4 py-2">{{ $document->deleted_at ? $document->deleted_at->format('d-m-Y H:i') : 'N/A' }}</td>
                    <td class="px-4 py-2">{{ $document->uploader->name ?? 'N/A' }}</td>
                    <td class="icon-cell">
                        <form action="{{ route('documents.restore', $document->id) }}" 
                              method="POST" 
                              class="document-action-form"
                              onsubmit="return confirm('Weet je zeker dat je dit document wilt herstellen?');">
                            @csrf
                            <button type="submit" 
                                    title="Herstellen"
                                    class="document-action-btn"
                                    style="color: #10B981;">
                                ↩️
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center">Geen verwijderde documenten gevonden</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="documents-footer-links">
        @if(isset($employee))
            <a href="{{ route('employer.employee.documents', $employee->id) }}" class="documents-footer-link" style="color: var(--primary-color);">← Terug naar {{ $employee->name }}</a>
        @elseif(auth()->user()->role === 'administration_office')
            <a href="{{ route('administration.dashboard') }}" class="documents-footer-link" style="color: var(--primary-color);">← Terug naar Dashboard</a>
        @else
            <a href="{{ route('employer.documents') }}" class="documents-footer-link" style="color: var(--primary-color);">← Terug naar Documenten</a>
        @endif
    </div>
</section>
@endsection
