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
    
    <table>
        <thead>
            <tr>
                <th>Medewerker</th>
                <th>Document Naam</th>
                <th>Type</th>
                <th>Periode</th>
                <th>Verwijderd op</th>
                <th>Verwijderd door</th>
                <th class="icon-cell">Acties</th>
            </tr>
        </thead>
        <tbody>
            @forelse($documents as $document)
            <tr>
                <td>{{ $document->employee->name ?? 'N/A' }}</td>
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
                <td>{{ $document->deleted_at ? $document->deleted_at->format('d-m-Y H:i') : 'N/A' }}</td>
                <td>{{ $document->uploader->name ?? 'N/A' }}</td>
                <td class="icon-cell">
                    <form action="{{ route('documents.restore', $document->id) }}" 
                          method="POST" 
                          class="documents-action-form"
                          onsubmit="return confirm('Weet je zeker dat je dit document wilt herstellen?');">
                        @csrf
                        <button type="submit" 
                                title="Herstellen"
                                class="documents-action-button"
                                style="color: #10B981;">
                            ↩️
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center;">Geen verwijderde documenten gevonden</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="documents-footer-links">
        @if(isset($employee))
            <a href="{{ route('employer.employee.documents', $employee->id) }}" class="documents-footer-link" style="color: var(--primary-color);">← Terug naar {{ $employee->name }}</a>
            <span class="documents-footer-separator">|</span>
            <a href="{{ auth()->user()->role === 'administration_office' ? route('administration.dashboard') : route('employer.dashboard') }}" class="documents-footer-link" style="color: var(--primary-color);">Dashboard</a>
        @elseif(isset($company) && auth()->user()->role === 'administration_office')
            <a href="{{ route('administration.company.documents', $company->id) }}" class="documents-footer-link" style="color: var(--primary-color);">← Terug naar {{ $company->name }}</a>
            <span class="documents-footer-separator">|</span>
            <a href="{{ route('administration.dashboard') }}" class="documents-footer-link" style="color: var(--primary-color);">Dashboard</a>
        @elseif(auth()->user()->role === 'administration_office')
            <a href="{{ route('administration.documents') }}" class="documents-footer-link" style="color: var(--primary-color);">← Terug naar Documenten</a>
            <span class="documents-footer-separator">|</span>
            <a href="{{ route('administration.dashboard') }}" class="documents-footer-link" style="color: var(--primary-color);">Dashboard</a>
        @else
            <a href="{{ route('employer.documents') }}" class="documents-footer-link" style="color: var(--primary-color);">← Terug naar Documenten</a>
            <span class="documents-footer-separator">|</span>
            <a href="{{ route('employer.dashboard') }}" class="documents-footer-link" style="color: var(--primary-color);">Dashboard</a>
        @endif
    </div>
</section>
@endsection
