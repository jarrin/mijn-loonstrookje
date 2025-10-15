@extends('layout.Layout')

@section('title', '2FA Verificatie - Mijn Loonstrookje')

@section('content')
<<<<<<< HEAD
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
            <button type="button" id="toggle-recovery">
                Gebruik een herstelcode
            </button>
        </div>

        <button type="submit">Verifiëren</button>
    </form>
=======
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Twee-factor authenticatie</h4>
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">
                    Voer je authenticatiecode in of gebruik een van je herstelcodes.
                </p>

                <form method="POST" action="{{ route('two-factor.login') }}">
                    @csrf

                    <div class="mb-3" id="code-section">
                        <label for="code" class="form-label">Authenticatiecode</label>
                        <input type="text" 
                               class="form-control @error('code') is-invalid @enderror" 
                               id="code" 
                               name="code" 
                               placeholder="123456"
                               autofocus>
                        @error('code')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3 d-none" id="recovery-section">
                        <label for="recovery_code" class="form-label">Herstelcode</label>
                        <input type="text" 
                               class="form-control @error('recovery_code') is-invalid @enderror" 
                               id="recovery_code" 
                               name="recovery_code" 
                               placeholder="abc-def-ghi">
                        @error('recovery_code')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <button type="button" id="toggle-recovery" class="btn btn-link p-0">
                            Gebruik een herstelcode
                        </button>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Verifiëren</button>
                </form>
            </div>
        </div>
    </div>
>>>>>>> origin/2FaResearchTesting
</div>

<script>
document.getElementById('toggle-recovery').addEventListener('click', function() {
    const codeSection = document.getElementById('code-section');
    const recoverySection = document.getElementById('recovery-section');
    const toggleBtn = document.getElementById('toggle-recovery');
    
<<<<<<< HEAD
    if (codeSection.style.display === 'none') {
        codeSection.style.display = 'block';
        recoverySection.style.display = 'none';
=======
    if (codeSection.classList.contains('d-none')) {
        codeSection.classList.remove('d-none');
        recoverySection.classList.add('d-none');
>>>>>>> origin/2FaResearchTesting
        toggleBtn.textContent = 'Gebruik een herstelcode';
        document.getElementById('code').focus();
        document.getElementById('recovery_code').value = '';
    } else {
<<<<<<< HEAD
        codeSection.style.display = 'none';
        recoverySection.style.display = 'block';
=======
        codeSection.classList.add('d-none');
        recoverySection.classList.remove('d-none');
>>>>>>> origin/2FaResearchTesting
        toggleBtn.textContent = 'Gebruik authenticatiecode';
        document.getElementById('recovery_code').focus();
        document.getElementById('code').value = '';
    }
});
</script>
@endsection
