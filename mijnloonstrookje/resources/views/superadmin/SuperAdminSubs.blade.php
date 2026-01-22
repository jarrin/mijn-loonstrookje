@extends('layout.Layout')

@section('title', 'Abonnementen - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="superadmin-page-title">Abonnementen Beheer</h1>
    <p class="superadmin-page-subtitle">Hier komen alle abonnementen te staan.</p>
    
    <div class="superadmin-actions-container">
        <a href="{{ route('superadmin.dashboard') }}" class="superadmin-button-secondary">Terug naar Dashboard</a>
        <a href="{{ route('superadmin.logs') }}" class="superadmin-button-primary">Logs</a>
        <a href="{{ route('superadmin.facturation') }}" class="superadmin-button-primary">Facturatie</a>
    </div>
</section>
<section>

    <h2 class="superadmin-subs-section-title">Alle abonnementen</h2>

    <div class="superadmin-subs-grid">
    @foreach ($subscriptions as $subscription)
        @php
            $isEditing = request('edit') == $subscription->id;
        @endphp
        <div class="superadmin-subscription-tile">
            @if(! $isEditing)
                {{-- Alleen lezen modus --}}
                <p><strong>Naam:</strong></p>
                <p>{{ $subscription->name }}</p>

                <p><strong>Kernpunten:</strong></p>
                <ul>
                    @if($subscription->feature_1)
                        <li>{{ $subscription->feature_1 }}</li>
                    @endif
                    @if($subscription->feature_2)
                        <li>{{ $subscription->feature_2 }}</li>
                    @endif
                    @if($subscription->feature_3)
                        <li>{{ $subscription->feature_3 }}</li>
                    @endif
                </ul>

                <p><strong>Prijs:</strong></p>
                <p>€ {{ number_format($subscription->price, 2, ',', '.') }}</p>

                <p><strong>Plan:</strong></p>
                <p>{{ $subscription->subscription_plan }}</p>

                <form method="GET" action="{{ route('superadmin.subscriptions') }}">
                    <input type="hidden" name="edit" value="{{ $subscription->id }}">
                    <button type="submit">Bewerken</button>
                </form>
            @else
                {{-- Bewerk modus --}}
                <form method="POST" action="{{ route('superadmin.subscriptions.update', $subscription->id) }}">
                    @csrf
                    @method('PUT')

                    <div>
                        <p><strong>Naam:</strong></p>
                        <p>
                            <input type="text" name="name" value="{{ old('name', $subscription->name) }}">
                        </p>
                    </div>

                    <div>
                        <p><strong>Kernpunten:</strong></p>
                        <ul>
                            <li>
                                <input type="text" name="feature_1" value="{{ old('feature_1', $subscription->feature_1) }}">
                            </li>
                            <li>
                                <input type="text" name="feature_2" value="{{ old('feature_2', $subscription->feature_2) }}">
                            </li>
                            <li>
                                <input type="text" name="feature_3" value="{{ old('feature_3', $subscription->feature_3) }}">
                            </li>
                        </ul>
                    </div>

                    <div>
                        <p><strong>Prijs:</strong></p>
                        <p>
                            <input type="number" step="0.01" name="price" value="{{ old('price', $subscription->price) }}">
                        </p>
                    </div>

                    <div>
                        <p><strong>Plan:</strong></p>
                        <p>
                            <input type="text" name="subscription_plan" value="{{ old('subscription_plan', $subscription->subscription_plan) }}">
                        </p>
                    </div>

                    <div>
                        <button type="submit">Opslaan</button>
                    </div>
                </form>
            @endif
        </div>
    @endforeach
    </div>

</section>

<section style="margin-top: 2rem;">
    <h2>Custom abonnementen</h2>
    <p style="color: #6b7280; margin-bottom: 1rem;">Beheer hier alle custom abonnementen.</p>

    <!-- Add new custom subscription form -->
    <div style="margin-bottom: 1.5rem; padding: 16px 24px; background: white; border-radius: 10px; box-shadow: 0 4px 20px 0 rgba(0, 0, 0, 0.15);">
        <form method="POST" action="{{ route('superadmin.custom-subscriptions.store') }}" style="display: flex; gap: 1rem; align-items: center;">
            @csrf
            <div style="width: 160px;">
                <input type="number" step="0.01" name="price" placeholder="Prijs" required style="width: 100%; padding: 12px 16px; border: 1px solid #D4D4D4; border-radius: 8px; font-size: 16px;">
            </div>
            <div style="width: 200px;">
                <select name="billing_period" required style="width: 100%; padding: 12px 16px; border: 1px solid #D4D4D4; border-radius: 8px; font-size: 16px; color: #6b7280;">
                    <option value="">Betalings termijn</option>
                    <option value="maandelijks">Maandelijks</option>
                    <option value="jaarlijks">Jaarlijks</option>
                </select>
            </div>
            <div style="width: 200px;">
                <input type="number" name="max_users" placeholder="Aantal gebruikers" required min="1" style="width: 100%; padding: 12px 16px; border: 1px solid #D4D4D4; border-radius: 8px; font-size: 16px;">
            </div>
            <div style="margin-left: auto;">
                <button type="submit" style="padding: 12px 24px; background: #3b82f6; color: white; border: none; border-radius: 8px; cursor: pointer; font-size: 16px; font-weight: 500; white-space: nowrap; display: flex; align-items: center; gap: 8px;">
                    Toevoegen
                    <span style="font-size: 18px;">+</span>
                </button>
            </div>
        </form>
    </div>



    <!-- Custom subscriptions table -->
    <table id="custom-subscriptions-table">
        <thead>
            <tr>
                <th style="width: 30px;"></th>
                <th>Abonnements prijs</th>
                <th>Betalings termijn</th>
                <th>Max aantal gebruikers</th>
                <th>Bedrijven</th>
                <th style="text-align: right;">Acties</th>
            </tr>
        </thead>
        <tbody>
            @forelse($customSubscriptions as $customSub)

                
                <!-- Main subscription row -->
                <tr>
                    <!-- Expand/Collapse Arrow - ALWAYS SHOW -->
                    <td>
                        <button type="button" onclick="toggleExpandCustomSubscription({{ $customSub->id }})" id="chevron-{{ $customSub->id }}" style="background: transparent; border: none; cursor: pointer; padding: 0; line-height: 1; transition: transform 0.2s;">
                            {!! file_get_contents(resource_path('assets/icons/chevron-up.svg')) !!}
                        </button>
                    </td>
                    
                    <td>€ {{ number_format($customSub->price, 2, ',', '.') }}</td>
                    <td>{{ ucfirst($customSub->billing_period) }}</td>
                    <td>{{ $customSub->max_users }}</td>
                    <td>{{ $customSub->companies_count }}</td>
                    
                    <!-- Actions column - right aligned with plus and delete buttons -->
                    <td style="text-align: right;">
                        <div style="display: flex; gap: 0.75rem; justify-content: flex-end; align-items: center;">
                            <!-- Invite button - triggers expand with invite form -->
                            <button type="button" onclick="showInviteForm({{ $customSub->id }})" title="Uitnodigen" style="background: transparent; border: none; cursor: pointer; padding: 0; line-height: 1;">
                                {!! file_get_contents(resource_path('assets/icons/plus.svg')) !!}
                            </button>
                            
                            <!-- Delete button -->
                            <form method="POST" action="{{ route('superadmin.custom-subscriptions.destroy', $customSub) }}" style="margin: 0; display: inline;" onsubmit="return confirm('Weet je zeker dat je dit custom abonnement wilt verwijderen?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Verwijderen" style="background: transparent; border: none; cursor: pointer; padding: 0; line-height: 1;">
                                    {!! file_get_contents(resource_path('assets/icons/trashbin.svg')) !!}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                
                <!-- Expanded section -->
                @if($customSub->companies->count() > 0)
                    @foreach($customSub->companies as $company)
                        <tr class="custom-sub-expanded custom-sub-{{ $customSub->id }}-expanded" style="background-color: #f9fafb; display: none;">
                            @php
                                $employer = $company->users->where('role', 'employer')->first();
                                $employeeCount = $company->users->where('role', 'employee')->count();
                            @endphp
                                <td></td>
                                <td style="padding-left: 2rem; font-size: 14px;">
                                    {{ $company->name }}
                                </td>
                                <td style="font-size: 14px; color: #6b7280;">
                                    {{ $employer ? $employer->email : '-' }}
                                </td>
                                <td style="font-size: 14px;">
                                    {{ $employeeCount }}/{{ $customSub->max_users }}
                                </td>
                                <td></td>
                                <td style="text-align: right;">
                                    <form method="POST" action="{{ route('superadmin.custom-subscriptions.remove-company', ['customSubscription' => $customSub->id, 'company' => $company->id]) }}" style="margin: 0; display: inline;" onsubmit="return confirm('Weet je zeker dat je {{ $company->name }} wilt verwijderen van dit custom abonnement?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Bedrijf verwijderen" style="background: transparent; border: none; cursor: pointer; padding: 0; line-height: 1;">
                                            {!! file_get_contents(resource_path('assets/icons/trashbin.svg')) !!}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        </tr>
                    @endforeach
                @endif
                    
                <!-- Show pending invitations -->
                @if($customSub->invitations->count() > 0)
                    @foreach($customSub->invitations as $invitation)
                        <tr class="custom-sub-expanded custom-sub-{{ $customSub->id }}-expanded" style="background-color: #fffbeb; display: none;">
                                <td></td>
                                <td style="padding-left: 2rem; font-size: 14px; color: #92400e;">
                                    {{ $invitation->email }}
                                </td>
                                <td style="font-size: 14px; color: #92400e; font-style: italic;" colspan="2">
                                    Uitnodiging pending
                                </td>
                                <td style="font-size: 14px; color: #6b7280;">
                                    Verloopt: {{ $invitation->expires_at->format('d-m-Y') }}
                                </td>
                                <td style="text-align: right;">
                                    <form method="POST" action="{{ route('superadmin.invitations.cancel', $invitation) }}" style="margin: 0; display: inline;" onsubmit="return confirm('Weet je zeker dat je deze uitnodiging wilt verwijderen?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" title="Verwijderen" style="background: transparent; border: none; cursor: pointer; padding: 0; line-height: 1;">
                                            {!! file_get_contents(resource_path('assets/icons/trashbin.svg')) !!}
                                        </button>
                                    </form>
                                </td>
                        </tr>
                    @endforeach
                @endif
                    
                <!-- Invitation form row -->
                <tr id="invite-form-{{ $customSub->id }}" class="custom-sub-{{ $customSub->id }}-expanded" style="background-color: #eff6ff; display: none;">
                            <td></td>
                            <td colspan="5" style="padding: 1rem 0.75rem 1rem 2rem;">
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <span style="font-size: 14px; color: #374151; font-weight: 500;">Uitnodigen:</span>
                                    <form method="POST" action="{{ route('superadmin.custom-subscriptions.invite', $customSub) }}" style="display: flex; gap: 0.5rem; align-items: center; margin: 0; flex: 1;">
                                        @csrf
                                        <input type="email" name="email" placeholder="email@voorbeeld.nl" required autofocus style="padding: 8px 14px; border: 1px solid #D4D4D4; border-radius: 6px; font-size: 14px; flex: 1; max-width: 400px;">
                                        <button type="submit" style="background: #3b82f6; color: white; border: none; padding: 8px 16px; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 500; white-space: nowrap;">Uitnodigen</button>
                                        <button type="button" onclick="hideInviteForm({{ $customSub->id }})" style="background: transparent; border: none; color: #6b7280; font-size: 1.25rem; cursor: pointer; padding: 0 0.5rem; line-height: 1;">×</button>
                                    </form>
                                </div>
                            </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="padding: 2rem; text-align: center; color: #9ca3af; font-size: 0.875rem;">Geen custom abonnementen gevonden.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</section>

@push('scripts')
<script>
function toggleExpandCustomSubscription(id) {
    const rows = document.querySelectorAll('.custom-sub-' + id + '-expanded');
    const chevron = document.getElementById('chevron-' + id);
    const inviteForm = document.getElementById('invite-form-' + id);
    
    // Toggle all expanded rows (companies, invitations, and invite form)
    rows.forEach(row => {
        if (row.style.display === 'none' || row.style.display === '') {
            row.style.display = 'table-row';
        } else {
            row.style.display = 'none';
        }
    });
    
    // Rotate chevron icon
    if (chevron.style.transform === 'rotate(180deg)') {
        chevron.style.transform = 'rotate(0deg)';
    } else {
        chevron.style.transform = 'rotate(180deg)';
    }
}

function showInviteForm(id) {
    const rows = document.querySelectorAll('.custom-sub-' + id + '-expanded');
    const chevron = document.getElementById('chevron-' + id);
    const inviteForm = document.getElementById('invite-form-' + id);
    
    // First expand if not already expanded
    let isExpanded = false;
    rows.forEach(row => {
        if (row.style.display === 'table-row') {
            isExpanded = true;
        }
    });
    
    if (!isExpanded) {
        // Expand all rows
        rows.forEach(row => {
            row.style.display = 'table-row';
        });
        chevron.style.transform = 'rotate(180deg)';
    } else {
        // Just show the invite form
        inviteForm.style.display = 'table-row';
    }
    
    // Focus the email input
    setTimeout(() => {
        const emailInput = inviteForm.querySelector('input[type="email"]');
        if (emailInput) {
            emailInput.focus();
        }
    }, 100);
}

function hideInviteForm(id) {
    const inviteForm = document.getElementById('invite-form-' + id);
    inviteForm.style.display = 'none';
    
    // Clear the email input
    const emailInput = inviteForm.querySelector('input[type="email"]');
    if (emailInput) {
        emailInput.value = '';
    }
}
</script>
@endpush
@endsection
