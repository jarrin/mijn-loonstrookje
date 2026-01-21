@extends('layout.Layout')

@section('title', 'Logs - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="text-2xl mb-4">Systeem Logs</h1>
    
    <!-- Filters -->
    <form method="GET" class="mb-6 p-4 bg-gray-50 rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Actie</label>
                <select name="action" class="w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">Alle acties</option>
                    @foreach($actions as $action)
                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $action)) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bedrijf</label>
                <select name="company_id" class="w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">Alle bedrijven</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Van Datum</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tot Datum</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full border-gray-300 rounded-md shadow-sm">
            </div>
        </div>
        <div class="mt-4 flex gap-2">
            <button type="submit" class="text-white px-4 py-2 rounded" style="background-color: #3B82F6; hover:opacity-90;">Filter</button>
            <a href="{{ route('superadmin.logs') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Reset</a>
        </div>
    </form>
    
    <table>
        <thead>
            <tr>
                <th>Timestamp</th>
                <th>Gebruiker</th>
                <th>Bedrijf</th>
                <th>Actie</th>
                <th>Beschrijving</th>
            </tr>
        </thead>
        <tbody>
            @forelse($logs as $log)
            <tr>
                <td>{{ $log->created_at->format('d-m-Y H:i:s') }}</td>
                <td>{{ $log->user ? $log->user->name : 'N/A' }}</td>
                <td>
                    @if($log->company)
                        {{ $log->company->name }}
                    @elseif($log->user && $log->user->role === 'administration_office')
                        @php
                            $companies = $log->user->companies;
                            $count = $companies->count();
                            $allCompanies = $companies->pluck('name')->join(', ');
                        @endphp
                        @if($count === 1)
                            {{ $companies->first()->name }}
                        @elseif($count > 1)
                            <span title="{{ $allCompanies }}" style="cursor: help; border-bottom: 1px dotted #666;">
                                {{ $count }} Bedrijven
                            </span>
                        @else
                            N/A
                        @endif
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    <span class="px-2 py-1 rounded text-xs" style="background-color: {{ 
                        match($log->action) {
                            'login' => '#10B981',
                            'document_uploaded' => '#3B82F6',
                            'document_revised' => '#F59E0B',
                            'document_deleted' => '#EF4444',
                            'document_restored' => '#8B5CF6',
                            'employee_created' => '#06B6D4',
                            'admin_office_added' => '#EC4899',
                            default => '#6B7280'
                        }
                    }}; color: white;">
                        {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                    </span>
                </td>
                <td>{{ $log->description ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">Geen logs gevonden</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <!-- Pagination -->
    <div class="mt-4">
        {{ $logs->links() }}
    </div>
    
    <div class="mt-6 space-x-4">
        <a href="{{ route('superadmin.dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Terug naar Dashboard</a>
    </div>
</section>
@endsection
