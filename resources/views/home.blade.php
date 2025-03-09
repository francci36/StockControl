@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<div class="container my-5">
    <div class="text-center mb-4">
        <h1>Bienvenue sur notre site</h1>
        <p class="lead">Gérez vos stocks, commandes et fournisseurs facilement.</p>
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-4">
            <!-- Connexion -->
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h3 class="card-title">Connexion</h3>
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <label for="login-email">Email</label>
                            <input type="email" name="email" id="login-email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="login-password">Mot de passe</label>
                            <input type="password" name="password" id="login-password" class="form-control" required>
                        </div>
                        <div class="form-check mb-3">
                            <input type="checkbox" name="remember" id="remember" class="form-check-input">
                            <label for="remember" class="form-check-label">Se souvenir de moi</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Connexion</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <!-- Inscription -->
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h3 class="card-title">Inscription</h3>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        <div class="form-group">
                            <label for="register-name">Nom</label>
                            <input type="text" name="name" id="register-name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="register-email">Email</label>
                            <input type="email" name="email" id="register-email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="register-password">Mot de passe</label>
                            <input type="password" name="password" id="register-password" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirmer le mot de passe</label>
                            <input type="password" name="password_confirmation" id="register-password_confirmation" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Inscription</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
