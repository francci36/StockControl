@extends('layouts.app')

@section('title', 'Ajouter un fournisseur')

@section('content')
<div class="container">
    <h1>Ajouter un fournisseur</h1>
    <form method="POST" action="{{ route('suppliers.store') }}">
        @csrf
        <div class="form-group">
            <label for="name">Nom</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="telephone">Téléphone</label>
            <input type="text" name="telephone" id="telephone" class="form-control" required>
        </div>

        <!-- Ajouter un champ pour sélectionner les produits -->
        <div class="form-group">
            <label for="products">Produits</label>
            <select name="products[]" id="products" class="form-control" multiple>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
</div>
@endsection
