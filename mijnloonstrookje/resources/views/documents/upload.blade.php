@extends('layout.Layout')

@section('title', 'Document Uploaden - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="documents-page-title">Document Uploaden</h1>
    
    @if($errors->any())
        <div class="documents-alert-error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="documents-form-container">
        @csrf
        
        <div class="documents-form-group">
            <label for="employee_id" class="documents-form-label">Medewerker *</label>
            <select name="employee_id" id="employee_id" required class="documents-form-select" {{ isset($selectedEmployee) ? 'disabled' : '' }}>
                <option value="">Selecteer medewerker</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" 
                        {{ (old('employee_id') == $employee->id || ($selectedEmployee && $selectedEmployee->id == $employee->id)) ? 'selected' : '' }}>
                        {{ $employee->name }} ({{ $employee->email }})
                    </option>
                @endforeach
            </select>
            @if(isset($selectedEmployee))
                {{-- Hidden input to ensure employee_id is submitted when select is disabled --}}
                <input type="hidden" name="employee_id" value="{{ $selectedEmployee->id }}">
            @endif
        </div>
        
        <div class="documents-form-group">
            <label for="document_type" class="documents-form-label">Document Type *</label>
            <select name="document_type" id="document_type" required class="documents-form-select">
                <option value="">Selecteer type</option>
                <option value="payslip" {{ old('document_type') == 'payslip' ? 'selected' : '' }}>Loonstrook</option>
                <option value="annual_statement" {{ old('document_type') == 'annual_statement' ? 'selected' : '' }}>Jaaroverzicht</option>
                <option value="other" {{ old('document_type') == 'other' ? 'selected' : '' }}>Overig</option>
            </select>
        </div>
        
        <div id="period_type_field" class="documents-form-group">
            <label for="period_type" class="documents-form-label">Periode Type *</label>
            <select name="period_type" id="period_type" required class="documents-form-select">
                <option value="">Selecteer periode type</option>
                <option value="Maandelijks" {{ old('period_type') == 'Maandelijks' ? 'selected' : '' }}>Maandelijks</option>
                <option value="Weekelijks" {{ old('period_type') == 'Weekelijks' ? 'selected' : '' }}>Weekelijks</option>
                <option value="2-wekelijks" {{ old('period_type') == '2-wekelijks' ? 'selected' : '' }}>2-wekelijks</option>
                <option value="Jaarlijks" {{ old('period_type') == 'Jaarlijks' ? 'selected' : '' }}>Jaarlijks</option>
            </select>
        </div>
        
        <div class="documents-form-group">
            <label for="year" class="documents-form-label">Jaar *</label>
            <input type="number" name="year" id="year" 
                   value="{{ old('year', date('Y')) }}" 
                   min="2000" max="2100" required 
                   class="documents-form-input">
        </div>
        
        <div id="month_field" class="documents-form-group" style="display: none;">
            <label for="month" class="documents-form-label">Maand</label>
            <select name="month" id="month" class="documents-form-select">
                <option value="">Selecteer maand</option>
                <option value="1" {{ old('month') == '1' ? 'selected' : '' }}>Januari</option>
                <option value="2" {{ old('month') == '2' ? 'selected' : '' }}>Februari</option>
                <option value="3" {{ old('month') == '3' ? 'selected' : '' }}>Maart</option>
                <option value="4" {{ old('month') == '4' ? 'selected' : '' }}>April</option>
                <option value="5" {{ old('month') == '5' ? 'selected' : '' }}>Mei</option>
                <option value="6" {{ old('month') == '6' ? 'selected' : '' }}>Juni</option>
                <option value="7" {{ old('month') == '7' ? 'selected' : '' }}>Juli</option>
                <option value="8" {{ old('month') == '8' ? 'selected' : '' }}>Augustus</option>
                <option value="9" {{ old('month') == '9' ? 'selected' : '' }}>September</option>
                <option value="10" {{ old('month') == '10' ? 'selected' : '' }}>Oktober</option>
                <option value="11" {{ old('month') == '11' ? 'selected' : '' }}>November</option>
                <option value="12" {{ old('month') == '12' ? 'selected' : '' }}>December</option>
            </select>
        </div>
        
        <div id="week_field" class="documents-form-group" style="display: none;">
            <label for="week" class="documents-form-label">Week</label>
            <input type="number" name="week" id="week" 
                   value="{{ old('week') }}" 
                   min="1" max="53" 
                   class="documents-form-input">
        </div>
        
        <div class="documents-form-group">
            <label for="document" class="documents-form-label">PDF Document * (Max 10MB)</label>
            <input type="file" name="document" id="document" 
                   accept="application/pdf" required 
                   class="documents-form-input">
            <p class="documents-form-helper">Alleen PDF bestanden zijn toegestaan</p>
        </div>
        
        <div class="documents-form-group">
            <label for="note" class="documents-form-label">Notitie (optioneel)</label>
            <textarea name="note" id="note" rows="3" 
                      class="documents-form-textarea">{{ old('note') }}</textarea>
        </div>
        
        <div class="documents-button-group">
            <button type="submit" class="documents-button-primary" style="background-color: var(--primary-color);">
                Document Uploaden
            </button>
            <a href="{{ $cancelUrl ?? route('employer.employees') }}" class="documents-button-secondary">
                Annuleren
            </a>
        </div>
    </form>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const documentTypeSelect = document.getElementById('document_type');
    const periodTypeField = document.getElementById('period_type_field');
    const periodTypeSelect = document.getElementById('period_type');
    const monthField = document.getElementById('month_field');
    const weekField = document.getElementById('week_field');
    const monthInput = document.getElementById('month');
    const weekInput = document.getElementById('week');
    
    function updateDocumentTypeFields() {
        const documentType = documentTypeSelect.value;
        
        if (documentType === 'annual_statement') {
            // Hide period type for jaaroverzicht
            periodTypeField.style.display = 'none';
            periodTypeSelect.removeAttribute('required');
            periodTypeSelect.value = 'Jaarlijks';
            
            // Hide month and week fields
            monthField.style.display = 'none';
            weekField.style.display = 'none';
            monthInput.removeAttribute('required');
            weekInput.removeAttribute('required');
        } else {
            // Show period type for other document types
            periodTypeField.style.display = 'block';
            periodTypeSelect.setAttribute('required', 'required');
            updatePeriodFields();
        }
    }
    
    function updatePeriodFields() {
        const periodType = periodTypeSelect.value;
        
        // Hide all fields first
        monthField.style.display = 'none';
        weekField.style.display = 'none';
        monthInput.removeAttribute('required');
        weekInput.removeAttribute('required');
        
        // Show relevant field based on period type
        if (periodType === 'Maandelijks') {
            monthField.style.display = 'block';
            monthInput.setAttribute('required', 'required');
        } else if (periodType === 'Weekelijks' || periodType === '2-wekelijks') {
            weekField.style.display = 'block';
            weekInput.setAttribute('required', 'required');
        }
    }
    
    documentTypeSelect.addEventListener('change', updateDocumentTypeFields);
    periodTypeSelect.addEventListener('change', updatePeriodFields);
    
    // Initialize on page load
    updateDocumentTypeFields();
});
</script>
@endsection
