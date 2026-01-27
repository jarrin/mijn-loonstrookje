@extends('layout.Layout')

@section('title', 'Administratiekantoren - Mijn Loonstrookje')

@section('content')
<section>
    <div class="employer-office-header">
        <div>
            <h1 class="employer-page-title">Administratiekantoren</h1>
            <p>Beheer hier de administratiekantoren die toegang hebben tot jouw bedrijf.</p>
        </div>
        
        <div class="employer-add-office">
            <button class="employer-button-primary" type="button" onclick="openAddAdminOfficeModal()">
                Administratiekantoor toevoegen
            </button>
        </div>
    </div>

    <table id="admin-office-table">
        <thead>
            <tr>
                <th>Naam</th>
                <th>Email</th>
                <th>Status</th>
                <th class="icon-cell">Acties</th>
            </tr>
        </thead>
        <tbody>
            {{-- Active/linked admin offices --}}
            @forelse($adminOffices as $office)
                <tr>
                    <td>{{ $office->name }}</td>
                    <td>{{ $office->email }}</td>
                    <td>
                        @php
                            $status = $office->pivot->status ?? 'active';
                            $statusColors = match($status) {
                                'active' => ['bg' => 'rgba(4, 211, 0, 0.3)', 'text' => '#00BC0D', 'label' => 'Actief'],
                                'inactive' => ['bg' => 'rgba(107, 114, 128, 0.3)', 'text' => '#6B7280', 'label' => 'Inactief'],
                                default => ['bg' => 'rgba(4, 211, 0, 0.3)', 'text' => '#00BC0D', 'label' => 'Actief']
                            };
                        @endphp
                        <span style="display: inline-block; padding: 0.3rem 0.8rem; border-radius: 50px; font-size: 0.75rem; background-color: {{ $statusColors['bg'] }}; color: {{ $statusColors['text'] }};">
                            {{ $statusColors['label'] }}
                        </span>
                    </td>
                    <td class="icon-cell">
                        <button class="document-action-edit" type="button" onclick='openEditAdminOfficeModal(@json($office))' title="Bewerk administratiekantoor" class="employer-action-edit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-pen-icon lucide-square-pen"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/></svg>
                        </button>
                        <form action="{{ route('employer.admin-offices.destroy', $office) }}" method="POST" style="display:inline" onsubmit="return confirm('Weet je zeker dat je de toegang van dit administratiekantoor wilt intrekken?');">
                            @csrf
                            @method('DELETE')
                            <button class="document-action-delete" type="submit" title="Toegang intrekken" class="employer-action-delete">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2-icon lucide-trash-2"><path d="M10 11v6"/><path d="M14 11v6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
            @endforelse

            {{-- Pending invitations --}}
            @foreach($pendingInvitations as $invitation)
                <tr>
                    <td style="opacity: 0.6;">{{ $invitation->invitation_type === 'new_account' ? 'Nieuw account' : 'Bestaand account' }}</td>
                    <td>{{ $invitation->email }}</td>
                    <td>
                        <span style="display: inline-block; padding: 0.3rem 0.8rem; border-radius: 50px; font-size: 0.75rem; background-color: rgba(255, 165, 0, 0.3); color: #FF8C00;">
                            Uitnodiging verstuurd
                        </span>
                    </td>
                    <td class="icon-cell">
                        <form action="{{ route('invitation.delete', $invitation->id) }}" method="POST" style="display: inline;" 
                              onsubmit="return confirm('Weet je zeker dat je de uitnodiging voor {{ $invitation->email }} wilt verwijderen?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="document-action-delete" title="Uitnodiging verwijderen">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach

            {{-- Empty state --}}
            @if($adminOffices->isEmpty() && $pendingInvitations->isEmpty())
                <tr>
                    <td colspan="4">Geen administratiekantoren gevonden.</td>
                </tr>
            @endif
        </tbody>
    </table>
</section>

<!-- Add Admin Office Modal -->
<section>
    <div id="addAdminOfficeModal" class="employer-modal-overlay" style="display:none;">
        <div class="employer-modal-content">
            <div class="employer-modal-header">
                <h2 class="employer-modal-title">Administratiekantoor toevoegen</h2>
                <button type="button" onclick="closeAddAdminOfficeModal()" aria-label="Sluiten" class="employer-modal-close">&times;</button>
            </div>

            <form id="addAdminOfficeForm" action="{{ route('employer.admin-offices.invite') }}" method="POST" class="employer-modal-body">
                @csrf

                <div class="employer-form-description">
                    <p>
                        Voer het e-mailadres in. Als het administratiekantoor nog geen account heeft, ontvangt het een e-mail om een account aan te maken. Anders ontvangt het een uitnodiging voor toegang tot uw bedrijf.
                    </p>
                </div>

                <div class="employer-form-group">
                    <div>
                        <label for="addAdminOfficeEmail" class="employer-form-label">Email *</label>
                        <input id="addAdminOfficeEmail" name="email" type="email" required placeholder="administratie@example.com" class="employer-form-input" />
                    </div>
                </div>

                <div class="employer-modal-footer">
                    <button type="button" onclick="closeAddAdminOfficeModal()" class="employer-button-secondary">Annuleren</button>
                    <button type="submit" class="employer-button-primary">Uitnodiging Versturen</button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Edit Admin Office Modal -->
<section>
    <div id="editAdminOfficeModal" class="employer-modal-overlay" style="display:none;">
        <div class="employer-modal-content">
            <div class="employer-modal-header">
                <h2 class="employer-modal-title">Administratiekantoor bewerken</h2>
                <button type="button" onclick="closeEditAdminOfficeModal()" aria-label="Sluiten" class="employer-modal-close">&times;</button>
            </div>

            <form id="editAdminOfficeForm" method="POST" class="employer-modal-body">
                @csrf
                @method('PUT')

                <div class="employer-form-description">
                    <p>
                        Naam: <strong id="editAdminOfficeName"></strong><br>
                        Email: <strong id="editAdminOfficeEmail"></strong>
                    </p>
                </div>

                <div class="employer-form-group">
                    <div>
                        <label for="editAdminOfficeStatus" class="employer-form-label">Toegangsstatus</label>
                        <select id="editAdminOfficeStatus" name="status" required class="employer-form-input">
                            <option value="active">Actief</option>
                            <option value="inactive">Inactief</option>
                        </select>
                    </div>
                </div>

                <div class="employer-modal-footer">
                    <button type="button" onclick="closeEditAdminOfficeModal()" class="employer-button-secondary">Annuleren</button>
                    <button type="submit" class="employer-button-primary">Opslaan</button>
                </div>
            </form>
        </div>
    </div>
</section>

@push('scripts')
<script>
    function openAddAdminOfficeModal() {
        const modal = document.getElementById('addAdminOfficeModal');
        modal.style.display = 'flex';
    }

    function closeAddAdminOfficeModal() {
        const modal = document.getElementById('addAdminOfficeModal');
        modal.style.display = 'none';
        document.getElementById('addAdminOfficeForm').reset();
    }

    function openEditAdminOfficeModal(office) {
        const modal = document.getElementById('editAdminOfficeModal');
        const form = document.getElementById('editAdminOfficeForm');

        form.action = '{{ url('/employer/admin-offices') }}/' + office.id;

        document.getElementById('editAdminOfficeName').textContent = office.name || '';
        document.getElementById('editAdminOfficeEmail').textContent = office.email || '';
        document.getElementById('editAdminOfficeStatus').value = office.pivot?.status || 'active';

        modal.style.display = 'flex';
    }

    function closeEditAdminOfficeModal() {
        const modal = document.getElementById('editAdminOfficeModal');
        modal.style.display = 'none';
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        const addModal = document.getElementById('addAdminOfficeModal');
        const editModal = document.getElementById('editAdminOfficeModal');
        
        if (event.target === addModal) {
            closeAddAdminOfficeModal();
        }
        if (event.target === editModal) {
            closeEditAdminOfficeModal();
        }
    }
</script>
@endpush
@endsection
