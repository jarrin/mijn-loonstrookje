@extends('layout.Layout')

@section('title', 'Gebruikers - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="superadmin-page-title">Gebruikers</h1>
    <p class="superadmin-page-subtitle">Welkom {{ auth()->user()->name }}, Beheer hier alle gebruikers</p>

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
                        <button type="button" onclick='openEditUserModal(@json($user))' title="Bewerk gebruiker" class="superadmin-action-edit">
                            {!! file_get_contents(resource_path('assets/icons/Edit.svg')) !!}
                        </button>
                        <form action="{{ route('superadmin.users.destroy', $user) }}" method="POST" style="display:inline" onsubmit="return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?');">
                            @csrf
                            @method('DELETE')
                            <button id="delete-table-button" type="submit" title="Verwijder gebruiker" class="superadmin-action-delete">
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
    <div id="editUserModal" class="superadmin-modal-overlay" style="display:none;">
        <div class="superadmin-modal-content">
            <div class="superadmin-modal-header">
                <h2 class="superadmin-modal-title">Gebruiker bewerken</h2>
                <button type="button" onclick="closeEditUserModal()" aria-label="Sluiten" class="superadmin-modal-close">&times;</button>
            </div>

            <form id="editUserForm" method="POST" class="superadmin-modal-body">
                @csrf
                @method('PUT')

                <div class="superadmin-form-group">
                    <div>
                        <label for="editUserName" class="superadmin-form-label">Naam</label>
                        <input id="editUserName" name="name" type="text" class="superadmin-form-input" />
                    </div>

                    <div>
                        <label for="editUserCompany" class="superadmin-form-label">Bedrijf</label>
                        <input id="editUserCompany" type="text" disabled class="superadmin-form-input" />
                    </div>

                    <div>
                        <label for="editUserEmail" class="superadmin-form-label">Email</label>
                        <input id="editUserEmail" name="email" type="email" class="superadmin-form-input" />
                    </div>

                    <div>
                        <label for="editUserRole" class="superadmin-form-label">Type gebruiker</label>
                        <select id="editUserRole" name="role" class="superadmin-form-select">
                            <option value="employee">Werknemer</option>
                            <option value="employer">Werkgever</option>
                            <option value="administration_office">Administratiekantoor</option>
                            <option value="super_admin">Super admin</option>
                        </select>
                    </div>

                    <div>
                        <label for="editUserStatus" class="superadmin-form-label">Status</label>
                        <select id="editUserStatus" name="status" class="superadmin-form-select">
                            <option value="active">Active</option>
                            <option value="pending">Pending</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="superadmin-modal-footer">
                    <button type="button" onclick="closeEditUserModal()" class="superadmin-button-secondary">Annuleren</button>
                    <button type="submit" class="superadmin-button-primary">Opslaan</button>
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