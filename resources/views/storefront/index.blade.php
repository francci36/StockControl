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
    <!-- Barre de recherche -->
    <div class="col-12 mb-4">
        <form action="{{ route('storefront') }}" method="GET">
            <div class="input-group">
                <input type="text" name="search" class="form-control" 
                       placeholder="Rechercher un produit..." value="{{ request('search') }}">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Liste des produits -->
    @foreach($products as $product)
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <img src="{{ asset('storage/' . $product->image) }}" 
                 class="card-img-top" 
                 alt="{{ $product->name }}"
                 style="height: 200px; object-fit: cover;">
            
            <div class="card-body">
                <h5 class="card-title">{{ $product->name }}</h5>
                <p class="card-text text-muted">{{ Str::limit($product->description, 100) }}</p>
                
                <div class="d-flex justify-content-between align-items-center">
                    <span class="h5 text-primary">{{ number_format($product->price, 2) }} €</span>
                    <button class="btn btn-sm btn-outline-primary add-to-cart" 
                            data-product-id="{{ $product->id }}">
                        <i class="fas fa-cart-plus"></i> Ajouter
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Pagination -->
    <div class="col-12">
        {{ $products->appends(request()->query())->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
        $(document).ready(function() {
        // Ajout au panier
        $('.add-to-cart').click(function() {
            const productId = $(this).data('product-id');
            
            $.post('{{ route("cart.add") }}', {
                _token: '{{ csrf_token() }}',
                product_id: productId,
                quantity: 1
            }, function() {
                updateCartCount();
                toastr.success('Produit ajouté au panier');
            }).fail(function() {
                toastr.error('Une erreur est survenue');
            });
        });
    });
</script>
@endpush