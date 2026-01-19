@extends('layout.Layout')

@section('title', 'Medewerkers - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="text-2xl mb-4">Medewerkers Lijst</h1>
    
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
    
    <div class="mt-6 space-x-4">
        <button onclick="openInviteEmployeeModal()" class="text-white px-4 py-2 rounded hover:opacity-90" style="background-color: var(--primary-color);">Medewerker Toevoegen</button>
        <a href="{{ route('employer.dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 inline-block">Terug naar Dashboard</a>
        {{-- <a href="{{ route('employer.documents') }}" class="text-white px-4 py-2 rounded hover:opacity-90 inline-block" style="background-color: var(--primary-color);">Alle Documenten</a> --}}
    </div>
</section>

<!-- Invite Employee Modal -->
<section>
    <div id="inviteEmployeeModal" style="display:none; position:fixed; inset:0; z-index:50; background:rgba(15, 23, 42, 0.4); align-items:center; justify-content:center;">
        <div style="background:white; border-radius:0.75rem; width:100%; max-width:640px; box-shadow:0 10px 40px rgba(15,23,42,0.25);">
            <div style="display:flex; justify-content:space-between; align-items:center; padding:1.5rem 1.75rem; border-bottom:1px solid #e5e7eb;">
                <h2 style="font-size:1.5rem; font-weight:600; color:#111827; margin:0;">Medewerker Uitnodigen</h2>
                <button type="button" onclick="closeInviteEmployeeModal()" aria-label="Sluiten" style="background:transparent; border:none; font-size:1.25rem; cursor:pointer; color:#6b7280;">&times;</button>
            </div>

            <form id="inviteEmployeeForm" action="{{ route('employer.send.invitation') }}" method="POST" style="padding:1.75rem;">
                @csrf

                <div style="margin-bottom:1rem;">
                    <p style="color:#6b7280; font-size:0.875rem; line-height:1.5;">
                        Voer het e-mailadres van de werknemer in. Deze ontvangt een uitnodiging om zijn/haar account aan te maken.
                    </p>
                </div>

                <div style="display:flex; flex-direction:column; gap:1rem;">
                    <div>
                        <label for="inviteEmployeeEmail" style="display:block; font-size:0.875rem; font-weight:500; color:#374151; margin-bottom:0.25rem;">E-mailadres *</label>
                        <input 
                            id="inviteEmployeeEmail" 
                            name="email" 
                            type="email" 
                            required 
                            placeholder="werknemer@example.com"
                            style="width:100%; border-radius:0.75rem; border:1px solid #e5e7eb; padding:0.75rem 1rem; background:#f9fafb;" 
                        />
                    </div>
                </div>

                <div style="display:flex; justify-content:flex-end; gap:1rem; margin-top:1.75rem; border-top:1px solid #e5e7eb; padding-top:1.25rem;">
                    <button type="button" onclick="closeInviteEmployeeModal()" style="background:transparent; border:none; color:#6b7280; font-weight:500; padding:0.75rem 1.5rem; border-radius:9999px; cursor:pointer;">Annuleren</button>
                    <button type="submit" style="background-color: var(--primary-color); color:white; border:none; font-weight:500; padding:0.75rem 1.75rem; border-radius:9999px; cursor:pointer; transition: opacity 0.2s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">Uitnodiging Versturen</button>
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
