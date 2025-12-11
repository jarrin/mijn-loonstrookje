@extends('layout.Layout')

@section('title', 'Super Admin Dashboard - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="text-2xl mb-4">Super Admin Dashboard</h1>
    <p>Welkom {{ auth()->user()->name }}, je hebt volledige toegang tot het systeem.</p>

    @include('components.TableFilterBar', [
        'filters' => [
            [
                'label' => 'Type gebruiker',
                'options' => ['Bedrijf', 'Werknemer', 'Administratie bureau']
            ],
            [
                'label' => 'Alle gebruikers',
                'options' => ['Actief', 'Inactief', 'In afwachting']
            ],
            [
                'label' => 'Alle gebruikers',
                'options' => ['Alfabetisch', 'Datum', 'Status']
            ]
        ]
    ])

    <table id="superadmin-table">
        <thead>
            <tr>
                <th>Gebruiker</th>
                <th>Bedrijf</th>
                <th>Email</th>
                <th>Type</th>
                <th>Status</th>
                <th class="icon-cell">Acties</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->company->name ?? '-' }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role }}</td>
                    <td><span class="status-label">Ready</span></td>
                    <td class="icon-cell">
                        <form action="{{ route('superadmin.users.destroy', $user) }}" method="POST" style="display:inline" onsubmit="return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?');">
                            @csrf
                            @method('DELETE')
                            <button id="delete-table-button" type="submit" title="Verwijder gebruiker" class="text-red-600">
                                {!! file_get_contents(resource_path('assets/icons/trashbin.svg')) !!}
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Geen gebruikers gevonden.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</section>

<section>
    <!-- Edit User Modal -->
    <div id="editUserModal" style="display:none; position:fixed; inset:0; z-index:50; background:rgba(15, 23, 42, 0.4); align-items:center; justify-content:center;">
        <div style="background:white; border-radius:0.75rem; width:100%; max-width:640px; box-shadow:0 10px 40px rgba(15,23,42,0.25);">
            <div style="display:flex; justify-content:space-between; align-items:center; padding:1.5rem 1.75rem; border-bottom:1px solid #e5e7eb;">
                <h2 style="font-size:1.5rem; font-weight:600; color:#111827; margin:0;">Gebruiker bewerken</h2>
                <button type="button" onclick="closeEditUserModal()" aria-label="Sluiten" style="background:transparent; border:none; font-size:1.25rem; cursor:pointer; color:#6b7280;">&times;</button>
            </div>

            <form id="editUserForm" method="POST" style="padding:1.75rem;">
                @csrf
                @method('PUT')

                <div style="display:flex; flex-direction:column; gap:1rem;">
                    <div>
                        <label for="editUserName" style="display:block; font-size:0.875rem; font-weight:500; color:#374151; margin-bottom:0.25rem;">Naam</label>
                        <input id="editUserName" name="name" type="text" style="width:100%; border-radius:0.75rem; border:1px solid #e5e7eb; padding:0.75rem 1rem; background:#f9fafb;" />
                    </div>

                    <div>
                        <label for="editUserCompany" style="display:block; font-size:0.875rem; font-weight:500; color:#374151; margin-bottom:0.25rem;">Bedrijf</label>
                        <input id="editUserCompany" type="text" disabled style="width:100%; border-radius:0.75rem; border:1px solid #e5e7eb; padding:0.75rem 1rem; background:#f9fafb;" />
                    </div>

                    <div>
                        <label for="editUserEmail" style="display:block; font-size:0.875rem; font-weight:500; color:#374151; margin-bottom:0.25rem;">Email</label>
                        <input id="editUserEmail" name="email" type="email" style="width:100%; border-radius:0.75rem; border:1px solid #e5e7eb; padding:0.75rem 1rem; background:#f9fafb;" />
                    </div>

                    <div>
                        <label for="editUserRole" style="display:block; font-size:0.875rem; font-weight:500; color:#374151; margin-bottom:0.25rem;">Type gebruiker</label>
                        <select id="editUserRole" name="role" style="width:100%; border-radius:0.75rem; border:1px solid #e5e7eb; padding:0.75rem 1rem; background:#f9fafb;">
                            <option value="employee">Werknemer</option>
                            <option value="employer">Werkgever</option>
                            <option value="administration_office">Administratiekantoor</option>
                            <option value="super_admin">Super admin</option>
                        </select>
                    </div>

                    <div>
                        <label for="editUserStatus" style="display:block; font-size:0.875rem; font-weight:500; color:#374151; margin-bottom:0.25rem;">Status</label>
                        <select id="editUserStatus" name="status" style="width:100%; border-radius:0.75rem; border:1px solid #e5e7eb; padding:0.75rem 1rem; background:#f9fafb;">
                            <option value="active">Active</option>
                            <option value="pending">Pending</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div style="display:flex; justify-content:flex-end; gap:1rem; margin-top:1.75rem; border-top:1px solid #e5e7eb; padding-top:1.25rem;">
                    <button type="button" onclick="closeEditUserModal()" style="background:transparent; border:none; color:#6b7280; font-weight:500; padding:0.75rem 1.5rem; border-radius:9999px;">Annuleren</button>
                    <button type="submit" style="background:#111827; color:white; border:none; font-weight:500; padding:0.75rem 1.75rem; border-radius:9999px; cursor:pointer;">Opslaan</button>
                </div>
            </form>
        </div>
    </div>
</section>
@push('scripts')
<script>
    function openEditUserModal(user) {
        const modal = document.getElementById('editUserModal');
        const form = document.getElementById('editUserForm');

        form.action = '{{ url('/superadmin/users') }}/' + user.id;

        document.getElementById('editUserName').value = user.name || '';
        document.getElementById('editUserCompany').value = (user.company && user.company.name) ? user.company.name : '';
        document.getElementById('editUserEmail').value = user.email || '';
        document.getElementById('editUserRole').value = user.role || '';
        document.getElementById('editUserStatus').value = user.status || '';

        modal.style.display = 'flex';
    }

    function closeEditUserModal() {
        const modal = document.getElementById('editUserModal');
        modal.style.display = 'none';
    }
</script>
@endpush
@endsection