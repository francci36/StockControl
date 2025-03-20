<!-- resources/views/profile/edit.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Modifier le profil</h1>

    <!-- Success Message -->
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <!-- Profile Edit Form -->
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH') <!-- Spoofs a PATCH method -->

        <!-- Name Field -->
        <div class="form-group mb-3">
            <label for="name">Nom</label>
            <input type="text" name="name" id="name" value="{{ Auth::user()->name }}" class="form-control" required>
        </div>

        <!-- Email Field -->
        <div class="form-group mb-3">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ Auth::user()->email }}" class="form-control" required>
        </div>

        <!-- Password Field -->
        <div class="form-group mb-3">
            <label for="password">Nouveau mot de passe (Laissez vide si vous ne souhaitez pas changer le mot de passe)</label>
            <input type="password" name="password" id="password" class="form-control">
        </div>

        <!-- Confirm Password Field -->
        <div class="form-group mb-3">
            <label for="password_confirmation">Confirmer le nouveau mot de passe</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
        </div>

        <!-- Profile Photo Upload Field -->
        <div class="form-group mb-3">
            <label for="profile_photo">Photo de profil</label>
            <input type="file" name="profile_photo" id="profile_photo" class="form-control">
            <p class="text-muted mt-1">Formats acceptés : JPEG, PNG, JPG. Taille maximale : 2 Mo.</p>

            <!-- Display Current Profile Photo -->
            <div class="mt-3">
                <label>Photo actuelle :</label>
                <br>
                <img src="{{ Auth::user()->profile_photo_url ? asset('storage/' . Auth::user()->profile_photo_url) : 'https://via.placeholder.com/100' }}" 
                     alt="Photo actuelle" 
                     class="rounded-circle" width="100" height="100">
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>

    <!-- Delete Account Form -->
    <form action="{{ route('profile.destroy') }}" method="POST" class="mt-3">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Supprimer le compte</button>
    </form>
</div>
@endsection
