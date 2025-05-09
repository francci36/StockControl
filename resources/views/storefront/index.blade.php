@extends('layouts.storefront')

@section('title', 'Boutique')
@section('page-title', $activeCategory->name ?? 'Nos Produits')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('storefront') }}">Accueil</a></li>
    @if($activeCategory)
    <li class="breadcrumb-item active">{{ $activeCategory->name }}</li>
    @endif
@endsection

@section('content')
<div class="row">
    <!-- Barre de recherche améliorée -->
    <div class="col-12 mb-4">
        <form action="{{ route('storefront') }}" method="GET" class="search-form">
            <div class="row">
                <!-- Champ de recherche principal -->
                <div class="col-md-8 mb-2">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-right-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                        </div>
                        <input type="text" name="search" class="form-control border-left-0" 
                               placeholder="Rechercher un produit par nom, description..." 
                               value="{{ request('search') }}"
                               aria-label="Rechercher un produit">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search mr-1"></i> Rechercher
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Filtre par catégorie -->
                <div class="col-md-4 mb-2">
                    <select name="category" class="form-control">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" 
                                {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <!-- Filtres avancés -->
            <div class="row mt-2 advanced-filters" style="display: {{ request('min_price') || request('max_price') || request('in_stock') ? 'block' : 'none' }};">
                <div class="col-md-3 mb-2">
                    <label>Prix min</label>
                    <input type="number" name="min_price" class="form-control" 
                           value="{{ request('min_price') }}" placeholder="Min" min="0">
                </div>
                <div class="col-md-3 mb-2">
                    <label>Prix max</label>
                    <input type="number" name="max_price" class="form-control" 
                           value="{{ request('max_price') }}" placeholder="Max" min="0">
                </div>
                <div class="col-md-3 mb-2">
                    <label>Disponibilité</label>
                    <select name="in_stock" class="form-control">
                        <option value="">Tous</option>
                        <option value="1" {{ request('in_stock') == '1' ? 'selected' : '' }}>En stock</option>
                        <option value="0" {{ request('in_stock') == '0' ? 'selected' : '' }}>En rupture</option>
                    </select>
                </div>
                <div class="col-md-3 mb-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-secondary w-100">
                        <i class="fas fa-filter mr-1"></i> Filtrer
                    </button>
                </div>
            </div>
            
            <div class="text-right">
                <a href="#" class="toggle-filters text-muted small">
                    <i class="fas fa-sliders-h mr-1"></i> 
                    <span>{{ request('min_price') || request('max_price') || request('in_stock') ? 'Masquer' : 'Afficher' }} les filtres avancés</span>
                </a>
                @if(request('search') || request('category') || request('min_price') || request('max_price') || request('in_stock'))
                <a href="{{ route('storefront') }}" class="btn btn-sm btn-outline-danger ml-2">
                    <i class="fas fa-times mr-1"></i> Réinitialiser
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Message si aucun résultat -->
    @if($products->isEmpty())
    <div class="col-12">
        <div class="alert alert-info">
            <i class="fas fa-info-circle mr-2"></i>
            Aucun produit ne correspond à votre recherche.
            <a href="{{ route('storefront') }}" class="alert-link ml-2">Réinitialiser les filtres</a>
        </div>
    </div>
    @endif

    <!-- Liste des produits -->
    @foreach($products as $product)
    @if($product->categories->isNotEmpty() || $product->category_id)
    <div class="col-md-4 mb-4">
        <div class="card h-100 product-card">
            <img src="{{ asset('storage/' . ($product->image ?? 'default.jpg')) }}" 
                class="card-img-top" 
                alt="{{ $product->name }}" 
                style="height: 200px; object-fit: cover;">
            
            <div class="card-body d-flex flex-column">
                <h5 class="card-title">{{ $product->name }}</h5>
                <p class="card-text text-muted">{{ Str::limit($product->description, 100) }}</p>
                
                <div class="mt-auto">
                    <!-- Prix -->
                    <h6 class="text-primary mb-2">{{ number_format($product->price, 2, ',', ' ') }} €</h6>
                    
                    <!-- Catégorie et stock -->
                    <div class="d-flex justify-content-between mb-3">
                        <span class="badge badge-secondary">
                            <i class="fas fa-tag mr-1"></i> {{ $product->categories->first()->name ?? 'Non catégorisé' }}
                        </span>
                        <span class="badge {{ $product->quantity > 0 ? 'badge-success' : 'badge-danger' }}">
                            <i class="fas fa-boxes mr-1"></i> 
                            {{ $product->quantity > 0 ? $product->quantity.' en stock' : 'Rupture' }}
                        </span>
                    </div>

                    <!-- Bouton ajout au panier -->
                    <button class="btn btn-block btn-outline-primary add-to-cart" 
                            data-product-id="{{ $product->id }}"
                            data-product-quantity="{{ $product->quantity }}"
                            {{ $product->quantity <= 0 ? 'disabled' : '' }}>
                        <i class="fas fa-cart-plus mr-1"></i> 
                        {{ $product->quantity > 0 ? 'Ajouter au panier' : 'Indisponible' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endforeach

    <!-- Pagination -->
    <div class="col-12">
        {{ $products->appends(request()->query())->links() }}
    </div>
</div>
@endsection

@push('styles')
<style>
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .search-form {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .add-to-cart {
        transition: all 0.3s ease;
    }
    .add-to-cart:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }
    .fa-spinner {
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        100% { transform: rotate(360deg); }
    }
</style>
@endpush