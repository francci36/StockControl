@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Créer un produit pour {{ $supplier->name }}</h1>

    <form action="{{ route('products.store') }}" method="POST">
        @csrf

        <!-- Champ caché pour l'ID du fournisseur -->
        <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">

        <!-- Champ pour le nom du produit -->
        <div class="form-group">
            <label for="name">Nom du produit</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <!-- Champ pour la description du produit -->
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control"></textarea>
        </div>

        <!-- Champ pour le prix du produit -->
        <div class="form-group">
            <label for="price">Prix</label>
            <input type="number" name="price" id="price" class="form-control" step="0.01" required>
        </div>

        
        <!-- Bouton de soumission -->
        <button type="submit" class="btn btn-primary">Créer le produit</button>
    </form>
</div>
@endsection