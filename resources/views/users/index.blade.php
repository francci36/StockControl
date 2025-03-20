@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Profil Utilisateur</h1>

    <!-- Display the user's current details -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            Détails Utilisateur
        </div>
        <div class="card-body">
            <p><strong>ID :</strong> {{ Auth::user()->id }}</p>
            <p><strong>Nom :</strong> {{ Auth::user()->name }}</p>
            <p><strong>Email :</strong> {{ Auth::user()->email }}</p>

            <!-- Display current profile photo -->
            <p><strong>Photo de profil :</strong></p>
            <img src="{{ Auth::user()->profile_photo_url ? asset('storage/' . Auth::user()->profile_photo_url) : 'https://via.placeholder.com/100' }}" 
                 alt="Photo de profil de {{ Auth::user()->name }}" 
                 class="rounded-circle mb-3" width="100" height="100">
        </div>
    </div>

    <!-- Form to update profile photo -->
    <div class="card">
        <div class="card-header bg-secondary text-white">
            Mettre à jour la photo de profil
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Upload field -->
                <div class="form-group mb-3">
                    <label for="profile_photo">Nouvelle photo de profil</label>
                    <input type="file" name="profile_photo" id="profile_photo" class="form-control">
                    @error('profile_photo')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit button -->
                <button type="submit" class="btn btn-success">Mettre à jour</button>
            </form>
        </div>
    </div>
</div>
@endsection
