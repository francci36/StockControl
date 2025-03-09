@extends('layouts.app')

@section('title', 'Modifier le fournisseur')

@section('content')
<div class="container">
    <h1>Modifier le fournisseur</h1>
    <form method="POST" action="{{ route('suppliers.update', $supplier->id) }}">
        @csrf
        @method('PATCH')
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $supplier->name }}" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="{{ $supplier->email }}" required>
        </div>
        <div class="form-group">
            <label for="telephone">Téléphone</label>
            <input type="text" name="telephone" id="telephone" class="form-control" value="{{ $supplier->telephone }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
</div>
@endsection
