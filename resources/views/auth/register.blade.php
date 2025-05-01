@extends('layouts.guest')

@section('title', 'Inscription')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h1 class="card-title text-center mb-4">Inscription</h1>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Nom</label>
                            <input id="name" type="text" name="name" class="form-control form-control-lg" required autofocus placeholder="Entrez votre nom">
                        </div>
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" type="email" name="email" class="form-control form-control-lg" required placeholder="Entrez votre email">
                        </div>
                        <div class="form-group mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input id="password" type="password" name="password" class="form-control form-control-lg" required placeholder="Entrez votre mot de passe">
                        </div>
                        <div class="form-group mb-4">
                            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control form-control-lg" required placeholder="Confirmez votre mot de passe">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">S'inscrire</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
