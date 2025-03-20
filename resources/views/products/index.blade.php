@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Liste des produits</h1>

    <!-- Afficher les messages de succès -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Afficher les messages d'erreur -->
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Tableau des produits -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Nom</th>
                <th>Description</th>
                <th>Prix</th>
              
                <th>Fournisseur</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->description }}</td>
                    <td>{{ number_format($product->price, 2, ',', ' ') }} €</td>
                    
                    <td>{{ optional($product->supplier)->name }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Aucun produit disponible.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
     <!-- Pagination -->
     <div class="mt-4 d-flex justify-content-center">
        {{ $products->links() }}
    </div>
</div>
@endsection
