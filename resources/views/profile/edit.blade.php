<!-- resources/views/profile/edit.blade.php -->

@extends('layouts.app')

@section('title', 'Modifier le profil')

@section('content')
<div class="container">
    <h1>Modifier le profil</h1>

    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PATCH')

        <div class="mb-3">
            <label for="name" class="form-label">Nom</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Nouveau mot de passe</label>
            <input type="password" class="form-control" id="password" name="password">
            <small class="form-text text-muted">Laissez vide si vous ne souhaitez pas changer le mot de passe.</small>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>

    <form method="POST" action="{{ route('profile.destroy') }}" class="mt-4">
        @csrf
        @method('DELETE')

        <button type="submit" class="btn btn-danger">Supprimer le compte</button>
    </form>
</div>
@endsection
