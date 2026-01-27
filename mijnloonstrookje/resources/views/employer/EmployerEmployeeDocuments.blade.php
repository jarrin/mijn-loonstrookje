@extends('layout.Layout')

@section('title', 'Documenten - Mijn Loonstrookje')

@section('content')
<section>
    @if(auth()->user()->role === 'administration_office' && isset($employee))
        <div class="employer-back-link">
            <a href="{{ route('administration.company.employees', $employee->company_id) }}" style="color: var(--primary-color);">
                ← Terug naar Medewerkers
            </a>
        </div>
    @endif
    
    <h1 class="employer-page-title">Documenten van {{ $employee->name ?? 'Alle Medewerkers' }}</h1>
    
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

    @include('components.TableFilterBar', [
        'filters' => [
            [
                'label' => 'Type document',
                'options' => ['Loonstrook', 'Jaaroverzicht', 'Overig']
            ],
            [
                'label' => 'Periode',
                'options' => ['Deze maand', 'Vorige maand', 'Dit kwartaal', 'Dit jaar']
            ],
            [
                'label' => 'Sorteer op',
                'options' => ['Nieuwste eerst', 'Oudste eerst', 'Naam A-Z', 'Naam Z-A']
            ]
        ],
        'actionButton' => '<button onclick="openUploadModal()" class="filter-button-add">Document Uploaden</button>'
    ])
    
    @if(isset($documents))
    <table>
        <thead>
            <tr>
                @if(!isset($employee))
                <th>Medewerker</th>
                @endif
                <th>Document Naam</th>
                <th>Type</th>
                <th>Periode</th>
                <th>Versie</th>
                <th>Grootte</th>
                <th>Upload Datum</th>
                <th class="icon-cell">Acties</th>
            </tr>
        </thead>
        <tbody>
            @forelse($documents as $document)
            <tr>
                @if(!isset($employee))
                <td>{{ $document->employee->name ?? 'N/A' }}</td>
                @endif
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
                <td>
                    @php
                        $parentId = $document->parent_document_id ?? $document->id;
                        $allVersions = \App\Models\Document::where(function($q) use ($parentId) {
                            $q->where('parent_document_id', $parentId)
                              ->orWhere('id', $parentId);
                        })->orderBy('version')->get();
                        $maxVersion = $allVersions->max('version');
                        $isLatest = $document->version == $maxVersion;
                        $isOriginal = $document->version == 1.0 && !$document->parent_document_id;
                        $versionCount = $allVersions->count();
                    @endphp
                    
                    <div class="document-version-container">
                        <span class="document-version-number">v{{ number_format($document->version, 1) }}</span>
                        
                        @if($isLatest && $document->version > 1.0)
                            <span class="document-version-badge document-version-latest">
                                NIEUWSTE
                            </span>
                        @endif
                    </div>
                </td>
                <td>{{ $document->formatted_size }}</td>
                <td>{{ $document->created_at->format('d-m-Y H:i') }}</td>
                <td class="icon-cell">
                    <div class="document-actions-container">
                        <a href="{{ route('documents.view', $document->id) }}" 
                           target="_blank" 
                           title="Bekijken"
                           class="document-action-view">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-icon lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                        </a>
                        <a href="{{ route('documents.download', $document->id) }}" 
                           title="Downloaden"
                           class="document-action-download">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 15V3"/><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><path d="m7 10 5 5 5-5"/></svg>
                        </a>
                        <button onclick="openEditModal({{ $document->id }})" 
                           title="Bijwerken (nieuwe versie)"
                           class="document-action-edit"
                           style="background: none; border: none; cursor: pointer; padding: 0; color: var(--primary-color);">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-square-pen-icon lucide-square-pen"><path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.375 2.625a1 1 0 0 1 3 3l-9.013 9.014a2 2 0 0 1-.853.505l-2.873.84a.5.5 0 0 1-.62-.62l.84-2.873a2 2 0 0 1 .506-.852z"/></svg>
                        </button>
                        <form action="{{ route('documents.destroy', $document->id) }}" 
                              method="POST" 
                              style="display: inline;"
                              onsubmit="return confirm('Weet je zeker dat je dit document wilt verwijderen?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    title="Verwijderen"
                                    class="document-action-delete">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ isset($employee) ? '7' : '8' }}" style="text-align: center;">Geen documenten gevonden</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @else
    <p>Hier komen alle documenten te staan.</p>
    @endif
    
    <div class="employer-footer-nav">
        <a href="{{ isset($employee) ? route('documents.deleted', ['employee' => $employee->id]) : route('documents.deleted') }}" class="employer-button-primary">Verwijderde Documenten</a>
    </div>
</section>

<!-- Upload Document Modal -->
<div id="uploadModal" class="employer-modal-overlay" style="display:none;">
    <div class="employer-modal-content" style="max-width: 800px;">
        <div class="employer-modal-header">
            <h2 class="employer-modal-title">Document Uploaden</h2>
            <button type="button" onclick="closeUploadModal()" aria-label="Sluiten" class="employer-modal-close">&times;</button>
        </div>

        <form id="uploadForm" action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="employer-modal-body">
            @csrf
            
            @if($errors->any())
                <div class="employer-alert-error" style="margin-bottom: 1rem;">
                    <ul style="margin: 0; padding-left: 1.5rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="employer-form-group">
                <label for="upload_employee_id" class="employer-form-label">Medewerker *</label>
                <select name="employee_id" id="upload_employee_id" required class="employer-form-input" {{ isset($employee) ? 'disabled' : '' }}>
                    <option value="">Selecteer medewerker</option>
                    @php
                        $user = auth()->user();
                        if ($user->role === 'administration_office') {
                            if(isset($employee)) {
                                $employees = \App\Models\User::where('role', 'employee')
                                    ->where('company_id', $employee->company_id)
                                    ->orderBy('name')
                                    ->get();
                            } else {
                                $companyIds = $user->companies()->wherePivot('status', 'active')->pluck('companies.id');
                                $employees = \App\Models\User::where('role', 'employee')
                                    ->whereIn('company_id', $companyIds)
                                    ->orderBy('name')
                                    ->get();
                            }
                        } else {
                            $employees = \App\Models\User::where('role', 'employee')
                                ->where('company_id', $user->company_id)
                                ->orderBy('name')
                                ->get();
                        }
                    @endphp
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" {{ isset($employee) && $employee->id == $emp->id ? 'selected' : '' }}>
                            {{ $emp->name }} ({{ $emp->email }})
                        </option>
                    @endforeach
                </select>
                @if(isset($employee))
                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                @endif
            </div>
            
            <div class="employer-form-group">
                <label for="upload_document_type" class="employer-form-label">Document Type *</label>
                <select name="document_type" id="upload_document_type" required class="employer-form-input">
                    <option value="">Selecteer type</option>
                    <option value="payslip">Loonstrook</option>
                    <option value="annual_statement">Jaaroverzicht</option>
                    <option value="other">Overig</option>
                </select>
            </div>
            
            <div id="upload_period_type_field" class="employer-form-group">
                <label for="upload_period_type" class="employer-form-label">Periode Type *</label>
                <select name="period_type" id="upload_period_type" required class="employer-form-input">
                    <option value="">Selecteer periode type</option>
                    <option value="Maandelijks">Maandelijks</option>
                    <option value="Weekelijks">Weekelijks</option>
                    <option value="2-wekelijks">2-wekelijks</option>
                    <option value="Jaarlijks">Jaarlijks</option>
                </select>
            </div>
            
            <div class="employer-form-group">
                <label for="upload_year" class="employer-form-label">Jaar *</label>
                <input type="number" name="year" id="upload_year" 
                       value="{{ date('Y') }}" 
                       min="2000" max="2100" required 
                       class="employer-form-input">
            </div>
            
            <div id="upload_month_field" class="employer-form-group" style="display: none;">
                <label for="upload_month" class="employer-form-label">Maand</label>
                <select name="month" id="upload_month" class="employer-form-input">
                    <option value="">Selecteer maand</option>
                    <option value="1">Januari</option>
                    <option value="2">Februari</option>
                    <option value="3">Maart</option>
                    <option value="4">April</option>
                    <option value="5">Mei</option>
                    <option value="6">Juni</option>
                    <option value="7">Juli</option>
                    <option value="8">Augustus</option>
                    <option value="9">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
            </div>
            
            <div id="upload_week_field" class="employer-form-group" style="display: none;">
                <label for="upload_week" class="employer-form-label">Week</label>
                <input type="number" name="week" id="upload_week" 
                       min="1" max="53" 
                       class="employer-form-input">
            </div>
            
            <div class="employer-form-group">
                <label for="upload_document" class="employer-form-label">PDF Document * (Max 10MB)</label>
                <input type="file" name="document" id="upload_document" 
                       accept="application/pdf" required 
                       class="employer-form-input">
                <p style="font-size: 0.875rem; color: #6B7280; margin-top: 0.25rem;">Alleen PDF bestanden zijn toegestaan</p>
            </div>
            
            <div class="employer-form-group">
                <label for="upload_note" class="employer-form-label">Notitie (optioneel)</label>
                <textarea name="note" id="upload_note" rows="3" 
                          class="employer-form-input" style="resize: vertical;"></textarea>
            </div>

            <div class="employer-modal-footer">
                <button type="button" onclick="closeUploadModal()" class="employer-button-secondary">Annuleren</button>
                <button type="submit" class="employer-button-primary">Document Uploaden</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Document Modal -->
<div id="editModal" class="employer-modal-overlay" style="display:none;">
    <div class="employer-modal-content" style="max-width: 800px;">
        <div class="employer-modal-header">
            <h2 class="employer-modal-title">Document Bijwerken (Nieuwe Revisie)</h2>
            <button type="button" onclick="closeEditModal()" aria-label="Sluiten" class="employer-modal-close">&times;</button>
        </div>

        <form id="editForm" method="POST" enctype="multipart/form-data" class="employer-modal-body">
            @csrf
            
            <div class="employer-alert-info" style="background-color: #DBEAFE; border: 1px solid #93C5FD; color: #1E40AF; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem;">
                <strong>Let op:</strong> Het originele document blijft bewaard. Er wordt een nieuwe versie aangemaakt.
            </div>
            
            <div id="edit_document_info" style="background-color: #F9FAFB; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                <!-- Document info will be populated by JavaScript -->
            </div>
            
            <div class="employer-form-group">
                <label for="edit_document_type" class="employer-form-label">Document Type *</label>
                <select name="document_type" id="edit_document_type" required class="employer-form-input">
                    <option value="">Selecteer type</option>
                    <option value="payslip">Loonstrook</option>
                    <option value="annual_statement">Jaaroverzicht</option>
                    <option value="other">Overig</option>
                </select>
            </div>
            
            <div class="employer-form-group">
                <label for="edit_period_type" class="employer-form-label">Periode Type *</label>
                <select name="period_type" id="edit_period_type" required class="employer-form-input">
                    <option value="">Selecteer periode type</option>
                    <option value="Maandelijks">Maandelijks</option>
                    <option value="Weekelijks">Weekelijks</option>
                    <option value="2-wekelijks">2-wekelijks</option>
                    <option value="Jaarlijks">Jaarlijks</option>
                </select>
            </div>
            
            <div class="employer-form-group">
                <label for="edit_year" class="employer-form-label">Jaar *</label>
                <input type="number" name="year" id="edit_year" min="2000" max="2100" required class="employer-form-input">
            </div>
            
            <div id="edit_month_field" class="employer-form-group" style="display: none;">
                <label for="edit_month" class="employer-form-label">Maand</label>
                <select name="month" id="edit_month" class="employer-form-input">
                    <option value="">Selecteer maand</option>
                    <option value="1">Januari</option>
                    <option value="2">Februari</option>
                    <option value="3">Maart</option>
                    <option value="4">April</option>
                    <option value="5">Mei</option>
                    <option value="6">Juni</option>
                    <option value="7">Juli</option>
                    <option value="8">Augustus</option>
                    <option value="9">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
            </div>
            
            <div id="edit_week_field" class="employer-form-group" style="display: none;">
                <label for="edit_week" class="employer-form-label">Week</label>
                <input type="number" name="week" id="edit_week" min="1" max="53" class="employer-form-input">
            </div>
            
            <div class="employer-form-group">
                <label for="edit_document" class="employer-form-label">Nieuw Document (PDF) *</label>
                <input type="file" name="document" id="edit_document" accept=".pdf" required class="employer-form-input">
                <p style="font-size: 0.875rem; color: #6B7280; margin-top: 0.25rem;">Maximaal 10MB, alleen PDF bestanden</p>
            </div>
            
            <div class="employer-form-group">
                <label for="edit_note" class="employer-form-label">Notitie (optioneel)</label>
                <textarea name="note" id="edit_note" rows="3" 
                          class="employer-form-input" style="resize: vertical;" 
                          placeholder="Bijv: Gecorrigeerde bedragen, aangepaste uren, etc."></textarea>
            </div>

            <div class="employer-modal-footer">
                <button type="button" onclick="closeEditModal()" class="employer-button-secondary">Annuleren</button>
                <button type="submit" class="employer-button-primary">Nieuwe Versie Opslaan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// Upload Modal Functions
function openUploadModal() {
    document.getElementById('uploadModal').style.display = 'flex';
}

function closeUploadModal() {
    document.getElementById('uploadModal').style.display = 'none';
    document.getElementById('uploadForm').reset();
}

// Edit Modal Functions
function openEditModal(documentId) {
    const modal = document.getElementById('editModal');
    const form = document.getElementById('editForm');
    
    // Fetch document data
    fetch(`/api/documents/${documentId}`)
        .then(response => response.json())
        .then(data => {
            // Set form action
            form.action = `/documents/${documentId}`;
            
            // Populate document info
            const typeMap = {
                'payslip': 'Loonstrook',
                'annual_statement': 'Jaaroverzicht',
                'other': 'Overig'
            };
            
            let periodText = '';
            if(data.month) {
                const months = ['', 'Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'];
                periodText = `${months[data.month]} ${data.year}`;
            } else if(data.week) {
                periodText = `Week ${data.week}, ${data.year}`;
            } else {
                periodText = data.year;
            }
            
            document.getElementById('edit_document_info').innerHTML = `
                <h3 style="margin: 0 0 0.5rem 0; font-size: 1rem; font-weight: 600;">Huidige Document Informatie:</h3>
                <p style="margin: 0.25rem 0;"><strong>Medewerker:</strong> ${data.employee_name}</p>
                <p style="margin: 0.25rem 0;"><strong>Type:</strong> ${typeMap[data.type]}</p>
                <p style="margin: 0.25rem 0;"><strong>Periode:</strong> ${periodText}</p>
                <p style="margin: 0.25rem 0;"><strong>Huidige Versie:</strong> v${parseFloat(data.version).toFixed(1)}</p>
                <p style="margin: 0.25rem 0;"><strong>Nieuwe Versie:</strong> v${(parseFloat(data.version) + 0.1).toFixed(1)}</p>
            `;
            
            // Populate form fields
            document.getElementById('edit_document_type').value = data.type;
            document.getElementById('edit_period_type').value = data.period_type;
            document.getElementById('edit_year').value = data.year;
            document.getElementById('edit_month').value = data.month || '';
            document.getElementById('edit_week').value = data.week || '';
            
            // Trigger period type change to show/hide correct fields
            updateEditPeriodFields();
            
            modal.style.display = 'flex';
        })
        .catch(error => {
            console.error('Error fetching document:', error);
            alert('Er is een fout opgetreden bij het ophalen van document gegevens.');
        });
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
    document.getElementById('editForm').reset();
}

// Upload Modal Period Logic
document.getElementById('upload_document_type').addEventListener('change', function() {
    const documentType = this.value;
    const periodTypeField = document.getElementById('upload_period_type_field');
    const periodTypeSelect = document.getElementById('upload_period_type');
    
    if (documentType === 'annual_statement') {
        periodTypeField.style.display = 'none';
        periodTypeSelect.removeAttribute('required');
        periodTypeSelect.value = 'Jaarlijks';
        document.getElementById('upload_month_field').style.display = 'none';
        document.getElementById('upload_week_field').style.display = 'none';
        document.getElementById('upload_month').removeAttribute('required');
        document.getElementById('upload_week').removeAttribute('required');
    } else {
        periodTypeField.style.display = 'block';
        periodTypeSelect.setAttribute('required', 'required');
        updateUploadPeriodFields();
    }
});

document.getElementById('upload_period_type').addEventListener('change', updateUploadPeriodFields);

function updateUploadPeriodFields() {
    const periodType = document.getElementById('upload_period_type').value;
    const monthField = document.getElementById('upload_month_field');
    const weekField = document.getElementById('upload_week_field');
    const monthInput = document.getElementById('upload_month');
    const weekInput = document.getElementById('upload_week');
    
    monthField.style.display = 'none';
    weekField.style.display = 'none';
    monthInput.removeAttribute('required');
    weekInput.removeAttribute('required');
    
    if (periodType === 'Maandelijks') {
        monthField.style.display = 'block';
        monthInput.setAttribute('required', 'required');
    } else if (periodType === 'Weekelijks' || periodType === '2-wekelijks') {
        weekField.style.display = 'block';
        weekInput.setAttribute('required', 'required');
    }
}

// Edit Modal Period Logic
document.getElementById('edit_period_type').addEventListener('change', updateEditPeriodFields);

function updateEditPeriodFields() {
    const periodType = document.getElementById('edit_period_type').value;
    const monthField = document.getElementById('edit_month_field');
    const weekField = document.getElementById('edit_week_field');
    const monthInput = document.getElementById('edit_month');
    const weekInput = document.getElementById('edit_week');
    
    monthField.style.display = 'none';
    weekField.style.display = 'none';
    monthInput.removeAttribute('required');
    weekInput.removeAttribute('required');
    
    if (periodType === 'Maandelijks') {
        monthField.style.display = 'block';
        monthInput.setAttribute('required', 'required');
    } else if (periodType === 'Weekelijks' || periodType === '2-wekelijks') {
        weekField.style.display = 'block';
        weekInput.setAttribute('required', 'required');
    }
}

// Close modals when clicking outside
window.addEventListener('click', function(event) {
    const uploadModal = document.getElementById('uploadModal');
    const editModal = document.getElementById('editModal');
    
    if (event.target === uploadModal) {
        closeUploadModal();
    }
    if (event.target === editModal) {
        closeEditModal();
    }
});

// Show upload modal if there are errors on page load
@if($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        openUploadModal();
    });
@endif
</script>
@endpush
@endsection
