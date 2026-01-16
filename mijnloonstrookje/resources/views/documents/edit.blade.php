@extends('layout.Layout')

@section('title', 'Document Bijwerken - Mijn Loonstrookje')

@section('content')
<section>
    <h1 class="text-2xl mb-4">Document Bijwerken (Nieuwe Revisie)</h1>
    
    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
        <strong>Let op:</strong> Het originele document blijft bewaard. Er wordt een nieuwe versie (v{{ number_format($document->version + 0.1, 1) }}) aangemaakt.
    </div>
    
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <div class="mb-6 p-4 border rounded">
        <h3 class="font-bold mb-2">Huidige Document Informatie:</h3>
        <p><strong>Medewerker:</strong> {{ $document->employee->name }}</p>
        <p><strong>Type:</strong> 
            @switch($document->type)
                @case('payslip') Loonstrook @break
                @case('annual_statement') Jaaroverzicht @break
                @case('other') Overig @break
            @endswitch
        </p>
        <p><strong>Periode:</strong> 
            @if($document->month)
                {{ ['', 'Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'][$document->month] }} {{ $document->year }}
            @elseif($document->week)
                Week {{ $document->week }}, {{ $document->year }}
            @else
                {{ $document->year }}
            @endif
        </p>
        <p><strong>Huidige Versie:</strong> v{{ number_format($document->version, 1) }}</p>
        <p><strong>Originele Bestandsnaam:</strong> {{ $document->original_filename }}</p>
    </div>
    
    <form action="{{ route('documents.update', $document->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        
        <div>
            <label for="document_type" class="block mb-2">Document Type *</label>
            <select name="document_type" id="document_type" required class="w-full px-3 py-2 border rounded">
                <option value="">Selecteer type</option>
                <option value="payslip" {{ old('document_type', $document->type) == 'payslip' ? 'selected' : '' }}>Loonstrook</option>
                <option value="annual_statement" {{ old('document_type', $document->type) == 'annual_statement' ? 'selected' : '' }}>Jaaroverzicht</option>
                <option value="other" {{ old('document_type', $document->type) == 'other' ? 'selected' : '' }}>Overig</option>
            </select>
        </div>
        
        <div>
            <label for="period_type" class="block mb-2">Periode Type *</label>
            <select name="period_type" id="period_type" required class="w-full px-3 py-2 border rounded">
                <option value="">Selecteer periode type</option>
                <option value="Maandelijks" {{ old('period_type', $document->period_type) == 'Maandelijks' ? 'selected' : '' }}>Maandelijks</option>
                <option value="Weekelijks" {{ old('period_type', $document->period_type) == 'Weekelijks' ? 'selected' : '' }}>Weekelijks</option>
                <option value="2-wekelijks" {{ old('period_type', $document->period_type) == '2-wekelijks' ? 'selected' : '' }}>2-wekelijks</option>
                <option value="Jaarlijks" {{ old('period_type', $document->period_type) == 'Jaarlijks' ? 'selected' : '' }}>Jaarlijks</option>
            </select>
        </div>
        
        <div>
            <label for="year" class="block mb-2">Jaar *</label>
            <input type="number" name="year" id="year" min="2000" max="2100" required value="{{ old('year', $document->year) }}" class="w-full px-3 py-2 border rounded">
        </div>
        
        <div id="month_field" style="{{ in_array(old('period_type', $document->period_type), ['Maandelijks']) ? '' : 'display: none;' }}">
            <label for="month" class="block mb-2">Maand</label>
            <select name="month" id="month" class="w-full px-3 py-2 border rounded">
                <option value="">Selecteer maand</option>
                @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ old('month', $document->month) == $i ? 'selected' : '' }}>
                        {{ ['', 'Januari', 'Februari', 'Maart', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'December'][$i] }}
                    </option>
                @endfor
            </select>
        </div>
        
        <div id="week_field" style="{{ in_array(old('period_type', $document->period_type), ['Weekelijks', '2-wekelijks']) ? '' : 'display: none;' }}">
            <label for="week" class="block mb-2">Week</label>
            <input type="number" name="week" id="week" min="1" max="53" value="{{ old('week', $document->week) }}" class="w-full px-3 py-2 border rounded">
        </div>
        
        <div>
            <label for="document" class="block mb-2">Nieuw Document (PDF) *</label>
            <input type="file" name="document" id="document" accept=".pdf" required class="w-full px-3 py-2 border rounded">
            <small class="text-gray-600">Maximaal 10MB, alleen PDF bestanden</small>
        </div>
        
        <div>
            <label for="note" class="block mb-2">Notitie (optioneel)</label>
            <textarea name="note" id="note" rows="3" class="w-full px-3 py-2 border rounded" placeholder="Bijv: Gecorrigeerde bedragen, aangepaste uren, etc.">{{ old('note') }}</textarea>
        </div>
        
        <div class="flex gap-4">
            <button type="submit" class="text-white px-4 py-2 rounded hover:opacity-90 inline-block" style="background-color: var(--primary-color);">
                Nieuwe Versie Opslaan
            </button>
            <a href="{{ route('employer.employee.documents', $document->employee_id) }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 inline-block">
                Annuleren
            </a>
        </div>
    </form>
    
    <script>
        document.getElementById('period_type').addEventListener('change', function() {
            const periodType = this.value;
            const monthField = document.getElementById('month_field');
            const weekField = document.getElementById('week_field');
            
            if (periodType === 'Maandelijks') {
                monthField.style.display = 'block';
                weekField.style.display = 'none';
                document.getElementById('month').required = true;
                document.getElementById('week').required = false;
            } else if (periodType === 'Weekelijks' || periodType === '2-wekelijks') {
                monthField.style.display = 'none';
                weekField.style.display = 'block';
                document.getElementById('month').required = false;
                document.getElementById('week').required = true;
            } else {
                monthField.style.display = 'none';
                weekField.style.display = 'none';
                document.getElementById('month').required = false;
                document.getElementById('week').required = false;
            }
        });
    </script>
</section>
@endsection
