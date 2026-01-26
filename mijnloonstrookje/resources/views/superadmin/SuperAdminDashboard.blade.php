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
                'label' => 'Status',
                'options' => ['Actief', 'Inactief']
            ],
            [
                'label' => 'Sorteer op',
                'options' => ['A-Z', 'Z-A']
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
                    <td>
                        @php
                            $statusColors = match($user->status ?? 'active') {
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
                        <button class="document-action-edit" type="button" onclick='openEditUserModal(@json($user))' title="Bewerk gebruiker" class="superadmin-action-edit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-pen-icon lucide-square-pen"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/></svg>
                        </button>
                        <form action="{{ route('superadmin.users.destroy', $user) }}" method="POST" style="display:inline" onsubmit="return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?');">
                            @csrf
                            @method('DELETE')
                            <button class="document-action-delete" type="submit" title="Verwijder gebruiker" class="superadmin-action-delete">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2-icon lucide-trash-2"><path d="M10 11v6"/><path d="M14 11v6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
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
                            <option value="active">Actief</option>
                            <option value="inactive">Inactief</option>
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