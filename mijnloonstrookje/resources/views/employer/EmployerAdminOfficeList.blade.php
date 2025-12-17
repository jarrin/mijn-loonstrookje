@extends('layout.Layout')

@section('title', 'Administratiekantoren - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="text-2xl mb-4">Administratiekantoren</h1>
    <p>Beheer hier de administratiekantoren die toegang hebben tot jouw bedrijf.</p>

    <div style="margin-bottom:1rem;">
        <button type="button" onclick="openAddAdminOfficeModal()">
            + Administratiekantoor toevoegen
        </button>
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
            @forelse($adminOffices as $office)
                <tr>
                    <td>{{ $office->name }}</td>
                    <td>{{ $office->email }}</td>
                    <td><span class="status-label">{{ ucfirst($office->status) }}</span></td>
                    <td class="icon-cell">
                        <button type="button" onclick='openEditAdminOfficeModal(@json($office))' title="Bewerk administratiekantoor" style="background:transparent; border:none; cursor:pointer; margin-right:0.5rem;">
                            {!! file_get_contents(resource_path('assets/icons/Edit.svg')) !!}
                        </button>
                        <form action="{{ route('employer.admin-offices.destroy', $office) }}" method="POST" style="display:inline" onsubmit="return confirm('Weet je zeker dat je dit administratiekantoor wilt verwijderen?');">
                            @csrf
                            @method('DELETE')
                            <button id="delete-table-button" type="submit" title="Verwijder administratiekantoor" class="text-red-600">
                                {!! file_get_contents(resource_path('assets/icons/trashbin.svg')) !!}
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Geen administratiekantoren gevonden.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</section>

<!-- Add Admin Office Modal -->
<section>
    <div id="addAdminOfficeModal" style="display:none; position:fixed; inset:0; z-index:50; background:rgba(15, 23, 42, 0.4); align-items:center; justify-content:center;">
        <div style="background:white; border-radius:0.75rem; width:100%; max-width:640px; box-shadow:0 10px 40px rgba(15,23,42,0.25);">
            <div style="display:flex; justify-content:space-between; align-items:center; padding:1.5rem 1.75rem; border-bottom:1px solid #e5e7eb;">
                <h2 style="font-size:1.5rem; font-weight:600; color:#111827; margin:0;">Administratiekantoor toevoegen</h2>
                <button type="button" onclick="closeAddAdminOfficeModal()" aria-label="Sluiten" style="background:transparent; border:none; font-size:1.25rem; cursor:pointer; color:#6b7280;">&times;</button>
            </div>

            <form id="addAdminOfficeForm" action="{{ route('employer.admin-offices.store') }}" method="POST" style="padding:1.75rem;">
                @csrf

                <div style="display:flex; flex-direction:column; gap:1rem;">
                    <div>
                        <label for="addAdminOfficeName" style="display:block; font-size:0.875rem; font-weight:500; color:#374151; margin-bottom:0.25rem;">Naam *</label>
                        <input id="addAdminOfficeName" name="name" type="text" required style="width:100%; border-radius:0.75rem; border:1px solid #e5e7eb; padding:0.75rem 1rem; background:#f9fafb;" />
                    </div>

                    <div>
                        <label for="addAdminOfficeEmail" style="display:block; font-size:0.875rem; font-weight:500; color:#374151; margin-bottom:0.25rem;">Email *</label>
                        <input id="addAdminOfficeEmail" name="email" type="email" required style="width:100%; border-radius:0.75rem; border:1px solid #e5e7eb; padding:0.75rem 1rem; background:#f9fafb;" />
                    </div>

                    <div>
                        <label for="addAdminOfficePassword" style="display:block; font-size:0.875rem; font-weight:500; color:#374151; margin-bottom:0.25rem;">Wachtwoord *</label>
                        <input id="addAdminOfficePassword" name="password" type="password" required minlength="8" style="width:100%; border-radius:0.75rem; border:1px solid #e5e7eb; padding:0.75rem 1rem; background:#f9fafb;" />
                        <small style="color:#6b7280;">Minimaal 8 karakters</small>
                    </div>
                </div>

                <div style="display:flex; justify-content:flex-end; gap:1rem; margin-top:1.75rem; border-top:1px solid #e5e7eb; padding-top:1.25rem;">
                    <button type="button" onclick="closeAddAdminOfficeModal()" style="background:transparent; border:none; color:#6b7280; font-weight:500; padding:0.75rem 1.5rem; border-radius:9999px;">Annuleren</button>
                    <button type="submit" style="background:#111827; color:white; border:none; font-weight:500; padding:0.75rem 1.75rem; border-radius:9999px; cursor:pointer;">Toevoegen</button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Edit Admin Office Modal -->
<section>
    <div id="editAdminOfficeModal" style="display:none; position:fixed; inset:0; z-index:50; background:rgba(15, 23, 42, 0.4); align-items:center; justify-content:center;">
        <div style="background:white; border-radius:0.75rem; width:100%; max-width:640px; box-shadow:0 10px 40px rgba(15,23,42,0.25);">
            <div style="display:flex; justify-content:space-between; align-items:center; padding:1.5rem 1.75rem; border-bottom:1px solid #e5e7eb;">
                <h2 style="font-size:1.5rem; font-weight:600; color:#111827; margin:0;">Administratiekantoor bewerken</h2>
                <button type="button" onclick="closeEditAdminOfficeModal()" aria-label="Sluiten" style="background:transparent; border:none; font-size:1.25rem; cursor:pointer; color:#6b7280;">&times;</button>
            </div>

            <form id="editAdminOfficeForm" method="POST" style="padding:1.75rem;">
                @csrf
                @method('PUT')

                <div style="display:flex; flex-direction:column; gap:1rem;">
                    <div>
                        <label for="editAdminOfficeName" style="display:block; font-size:0.875rem; font-weight:500; color:#374151; margin-bottom:0.25rem;">Naam</label>
                        <input id="editAdminOfficeName" name="name" type="text" required style="width:100%; border-radius:0.75rem; border:1px solid #e5e7eb; padding:0.75rem 1rem; background:#f9fafb;" />
                    </div>

                    <div>
                        <label for="editAdminOfficeEmail" style="display:block; font-size:0.875rem; font-weight:500; color:#374151; margin-bottom:0.25rem;">Email</label>
                        <input id="editAdminOfficeEmail" name="email" type="email" required style="width:100%; border-radius:0.75rem; border:1px solid #e5e7eb; padding:0.75rem 1rem; background:#f9fafb;" />
                    </div>

                    <div>
                        <label for="editAdminOfficeStatus" style="display:block; font-size:0.875rem; font-weight:500; color:#374151; margin-bottom:0.25rem;">Status</label>
                        <select id="editAdminOfficeStatus" name="status" style="width:100%; border-radius:0.75rem; border:1px solid #e5e7eb; padding:0.75rem 1rem; background:#f9fafb;">
                            <option value="active">Active</option>
                            <option value="pending">Pending</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div style="display:flex; justify-content:flex-end; gap:1rem; margin-top:1.75rem; border-top:1px solid #e5e7eb; padding-top:1.25rem;">
                    <button type="button" onclick="closeEditAdminOfficeModal()" style="background:transparent; border:none; color:#6b7280; font-weight:500; padding:0.75rem 1.5rem; border-radius:9999px;">Annuleren</button>
                    <button type="submit" style="background:#111827; color:white; border:none; font-weight:500; padding:0.75rem 1.75rem; border-radius:9999px; cursor:pointer;">Opslaan</button>
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

        document.getElementById('editAdminOfficeName').value = office.name || '';
        document.getElementById('editAdminOfficeEmail').value = office.email || '';
        document.getElementById('editAdminOfficeStatus').value = office.status || 'active';

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
