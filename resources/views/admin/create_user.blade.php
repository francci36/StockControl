@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h1 class="card-title text-center mb-4">Créer un utilisateur</h1>
                    <form method="POST" action="{{ route('admin.users.create') }}">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Nom</label>
                            <input id="name" type="text" name="name" class="form-control" required autofocus placeholder="Entrez le nom">
                        </div>
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" type="email" name="email" class="form-control" required placeholder="Entrez l'email">
                        </div>
                        <div class="form-group mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input id="password" type="password" name="password" class="form-control" required placeholder="Entrez un mot de passe">
                        </div>
                        <div class="form-group mb-3">
                            <label for="role" class="form-label">Rôle</label>
                            <select id="role" name="role" class="form-control">
                                <option value="user">Utilisateur</option>
                                <option value="manager">Manager</option>
                                <option value="admin">Administrateur</option>
                            </select>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">Créer l'utilisateur</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
