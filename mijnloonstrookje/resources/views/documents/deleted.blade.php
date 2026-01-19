@extends('layout.Layout')

@section('title', 'Verwijderde Documenten - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="text-2xl mb-4">
        @if(isset($employee))
            Verwijderde Documenten van {{ $employee->name }}
        @elseif(isset($company))
            Verwijderde Documenten van {{ $company->name }}
        @else
            Verwijderde Documenten
        @endif
    </h1>
    
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
                          style="display: inline;"
                          onsubmit="return confirm('Weet je zeker dat je dit document wilt herstellen?');">
                        @csrf
                        <button type="submit" 
                                title="Herstellen"
                                style="background: none; border: none; color: #10B981; cursor: pointer; padding: 0; font-size: inherit;">
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
    
    <div class="mt-6 space-x-4">
        @if(isset($employee))
            <a href="{{ route('employer.employee.documents', $employee->id) }}" style="color: var(--primary-color); cursor: pointer;">← Terug naar {{ $employee->name }}</a>
            <span style="color: #9CA3AF;">|</span>
            <a href="{{ auth()->user()->role === 'administration_office' ? route('administration.dashboard') : route('employer.dashboard') }}" style="color: var(--primary-color); cursor: pointer;">Dashboard</a>
        @elseif(isset($company) && auth()->user()->role === 'administration_office')
            <a href="{{ route('administration.company.documents', $company->id) }}" style="color: var(--primary-color); cursor: pointer;">← Terug naar {{ $company->name }}</a>
            <span style="color: #9CA3AF;">|</span>
            <a href="{{ route('administration.dashboard') }}" style="color: var(--primary-color); cursor: pointer;">Dashboard</a>
        @elseif(auth()->user()->role === 'administration_office')
            <a href="{{ route('administration.documents') }}" style="color: var(--primary-color); cursor: pointer;">← Terug naar Documenten</a>
            <span style="color: #9CA3AF;">|</span>
            <a href="{{ route('administration.dashboard') }}" style="color: var(--primary-color); cursor: pointer;">Dashboard</a>
        @else
            <a href="{{ route('employer.documents') }}" style="color: var(--primary-color); cursor: pointer;">← Terug naar Documenten</a>
            <span style="color: #9CA3AF;">|</span>
            <a href="{{ route('employer.dashboard') }}" style="color: var(--primary-color); cursor: pointer;">Dashboard</a>
        @endif
    </div>
</section>
@endsection
