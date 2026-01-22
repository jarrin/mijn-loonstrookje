@extends('layout.Layout')

@section('title', '2FA Verificatie - Mijn Loonstrookje')

@section('content')
<div>
    <h1>Twee-factor authenticatie</h1>
    <p>Voer je authenticatiecode in of gebruik een van je herstelcodes.</p>

    <form method="POST" action="{{ route('two-factor.login') }}">
        @csrf

        <div id="code-section">
            <label for="code">Authenticatiecode</label>
            <input type="text" 
                   id="code" 
                   name="code" 
                   placeholder="123456"
                   autofocus>
            @error('code')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div id="recovery-section" style="display: none;">
            <label for="recovery_code">Herstelcode</label>
            <input type="text" 
                   id="recovery_code" 
                   name="recovery_code" 
                   placeholder="abc-def-ghi">
            @error('recovery_code')
                <div>{{ $message }}</div>
            @enderror
        </div>

        <div>
            <button type="button" id="toggle-recovery" class="border border-gray-500">
                Gebruik een herstelcode
            </button>
        </div>

        <button type="submit" class="border border-gray-500">Verifiëren</button>
    </form>
</div>

<script>
document.getElementById('toggle-recovery').addEventListener('click', function() {
    const codeSection = document.getElementById('code-section');
    const recoverySection = document.getElementById('recovery-section');
    const toggleBtn = document.getElementById('toggle-recovery');
    
    if (codeSection.style.display === 'none') {
        codeSection.style.display = 'block';
        recoverySection.style.display = 'none';
    if (codeSection.classList.contains('d-none')) {
        codeSection.classList.remove('d-none');
        recoverySection.classList.add('d-none');
        toggleBtn.textContent = 'Gebruik een herstelcode';
        document.getElementById('code').focus();
        document.getElementById('recovery_code').value = '';
    } else {

        toggleBtn.textContent = 'Gebruik authenticatiecode';
        document.getElementById('recovery_code').focus();
        document.getElementById('code').value = '';
    }
});
</script>
@endsection
