@extends('layout.Layout')

@section('title', 'Medewerkers - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="employer-page-title">Medewerkers Lijst</h1>
    
    @if(session('success'))
        <div class="employer-alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="employer-alert-error">
            {{ session('error') }}
        </div>
    @endif
    
    <table>
        <thead>
            <tr>
                <th>Naam</th>
                <th>Email</th>
                <th>Status</th>
                <th class="icon-cell">Acties</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees ?? [] as $employee)
            <tr style="cursor: pointer;" onclick="window.location='{{ route('employer.employee.documents', $employee->id) }}'">
                <td>{{ $employee->name }}</td>
                <td>{{ $employee->email }}</td>
                <td>Actief</td>
                <td class="icon-cell">{!! file_get_contents(resource_path('assets/icons/trashbin.svg')) !!}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center;">Geen medewerkers gevonden</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="employer-actions-container">
        <button onclick="openInviteEmployeeModal()" class="employer-button-primary">Medewerker Toevoegen</button>
        <a href="{{ route('employer.dashboard') }}" class="employer-button-secondary">Terug naar Dashboard</a>
        {{-- <a href="{{ route('employer.documents') }}" class="employer-button-primary">Alle Documenten</a> --}}
    </div>
</section>

<!-- Invite Employee Modal -->
<section>
    <div id="inviteEmployeeModal" class="employer-modal-overlay" style="display:none;">
        <div class="employer-modal-content">
            <div class="employer-modal-header">
                <h2 class="employer-modal-title">Medewerker Uitnodigen</h2>
                <button type="button" onclick="closeInviteEmployeeModal()" aria-label="Sluiten" class="employer-modal-close">&times;</button>
            </div>

            <form id="inviteEmployeeForm" action="{{ route('employer.send.invitation') }}" method="POST" class="employer-modal-body">
                @csrf

                <div class="employer-form-description">
                    <p>
                        Voer het e-mailadres van de werknemer in. Deze ontvangt een uitnodiging om zijn/haar account aan te maken.
                    </p>
                </div>

                <div class="employer-form-group">
                    <div>
                        <label for="inviteEmployeeEmail" class="employer-form-label">E-mailadres *</label>
                        <input 
                            id="inviteEmployeeEmail" 
                            name="email" 
                            type="email" 
                            required 
                            placeholder="werknemer@example.com"
                            class="employer-form-input" 
                        />
                    </div>
                </div>

                <div class="employer-modal-footer">
                    <button type="button" onclick="closeInviteEmployeeModal()" class="employer-button-secondary">Annuleren</button>
                    <button type="submit" class="employer-button-primary">Uitnodiging Versturen</button>
                </div>
            </form>
        </div>
    </div>
</section>

@push('scripts')
<script>
    function openInviteEmployeeModal() {
        const modal = document.getElementById('inviteEmployeeModal');
        modal.style.display = 'flex';
    }

    function closeInviteEmployeeModal() {
        const modal = document.getElementById('inviteEmployeeModal');
        modal.style.display = 'none';
        document.getElementById('inviteEmployeeForm').reset();
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('inviteEmployeeModal');
        if (event.target === modal) {
            closeInviteEmployeeModal();
        }
    }
</script>
@endpush
@endsection
