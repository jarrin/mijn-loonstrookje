@extends('layout.Layout')

@section('title', '2FA Verificatie - Mijn Loonstrookje')

@section('content')
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
</div>

<script>
document.getElementById('toggle-recovery').addEventListener('click', function() {
    const codeSection = document.getElementById('code-section');
    const recoverySection = document.getElementById('recovery-section');
    const toggleBtn = document.getElementById('toggle-recovery');
    
    if (codeSection.classList.contains('d-none')) {
        codeSection.classList.remove('d-none');
        recoverySection.classList.add('d-none');
        toggleBtn.textContent = 'Gebruik een herstelcode';
        document.getElementById('code').focus();
        document.getElementById('recovery_code').value = '';
    } else {
        codeSection.classList.add('d-none');
        recoverySection.classList.remove('d-none');
        toggleBtn.textContent = 'Gebruik authenticatiecode';
        document.getElementById('recovery_code').focus();
        document.getElementById('code').value = '';
    }
});
</script>
@endsection
