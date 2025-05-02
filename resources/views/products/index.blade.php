@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h1 class="mb-0">
                @if(request()->has('lowStock') && request()->lowStock == 1)
                    <span class="text-warning">⚠️ Produits à réapprovisionner</span>
                @else
                    Liste des produits
                @endif
            </h1>
        </div>
        <div class="card-body">
            
            <!-- Afficher les messages de succès -->
            @if (session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <!-- Afficher les messages d'erreur -->
            @if (session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            <!-- Tableau des produits -->
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Prix</th>
                        <th>Stock</th>
                        <th>Seuil minimal</th>
                        <th>Fournisseur</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td><i class="fas fa-box"></i> {{ $product->name }}</td>
                            <td>{{ $product->description }}</td>
                            <td><i class="fas fa-euro-sign"></i> {{ number_format($product->price, 2, ',', ' ') }} €</td>
                            <td>
                                @php
                                    $stockClass = optional($product->stock)->quantity <= optional($product->stock)->stock_threshold ? 'bg-danger text-white' : 'bg-success text-white';
                                @endphp
                                <span class="badge {{ $stockClass }}">
                                    {{ optional($product->stock)->quantity ?? 'Non défini' }}
                                </span>
                            </td>
                            <td>{{ optional($product->stock)->stock_threshold ?? 'Non défini' }}</td>
                            <td><i class="fas fa-truck"></i> {{ optional($product->supplier)->name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                @if(request()->has('lowStock') && request()->lowStock == 1)
                                    Aucun produit à réapprovisionner.
                                @else
                                    Aucun produit disponible.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="mt-4 d-flex justify-content-center">
                {{ $products->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection
