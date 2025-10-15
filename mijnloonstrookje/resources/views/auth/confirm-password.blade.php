@extends('layout.Layout')

@section('title', 'Bevestig Wachtwoord - Mijn Loonstrookje')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Bevestig je wachtwoord</h4>
            </div>
            <div class="card-body">
                <p class="text-muted mb-4">
                    Bevestig je wachtwoord voordat je doorgaat met deze beveiligde actie.
                </p>

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="password" class="form-label">Wachtwoord</label>
                        <input type="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               id="password" 
                               name="password" 
                               required 
                               autofocus>
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">Annuleren</a>
                        <button type="submit" class="btn btn-primary">Bevestigen</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection