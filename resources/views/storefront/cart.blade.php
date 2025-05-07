@extends('layouts.storefront')

@section('title', 'Mon Panier')
@section('page-title', 'Mon Panier')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('storefront') }}">Accueil</a></li>
    <li class="breadcrumb-item active">Panier</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        @if(count($cart) > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart as $id => $item)
                        <tr>
                            <td>
                                <img src="{{ asset('storage/' . $item['image']) }}" 
                                     width="50" 
                                     class="mr-2"
                                     alt="{{ $item['name'] }}">
                                {{ $item['name'] }}
                            </td>
                            <td>{{ number_format($item['price'], 2) }} €</td>
                            <td>{{ $item['quantity'] }}</td>
                            <td>{{ number_format($item['price'] * $item['quantity'], 2) }} €</td>
                            <td>
                                <button class="btn btn-sm btn-danger remove-from-cart" 
                                        data-product-id="{{ $id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right"><strong>Total :</strong></td>
                            <td colspan="2">
                                {{ number_format(array_sum(array_map(function($item) { 
                                    return $item['price'] * $item['quantity']; 
                                }, $cart)), 2) }} €
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="text-right">
                <button class="btn btn-danger" id="clear-cart">
                    <i class="fas fa-trash"></i> Vider le panier
                </button>
                
                @if(Route::has('checkout'))
                    <a href="{{ route('checkout') }}" class="btn btn-primary">
                        <i class="fas fa-credit-card"></i> Passer la commande
                    </a>
                @else
                    <button class="btn btn-primary" disabled>
                        <i class="fas fa-credit-card"></i> Passer la commande (bientôt disponible)
                    </button>
                @endif
            </div>
        @else
            <div class="alert alert-info">
                Votre panier est vide.
            </div>
            <a href="{{ route('storefront') }}" class="btn btn-primary">
                <i class="fas fa-shopping-bag"></i> Continuer vos achats
            </a>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Supprimer un produit du panier
        $('.remove-from-cart').click(function() {
            if(!confirm('Êtes-vous sûr de vouloir retirer ce produit ?')) return;
            
            const productId = $(this).data('product-id');
            
            $.post('{{ route("cart.remove") }}', {
                _token: '{{ csrf_token() }}',
                product_id: productId
            }, function() {
                location.reload();
            }).fail(function() {
                alert('Une erreur est survenue');
            });
        });

        // Vider le panier
        $('#clear-cart').click(function() {
            if(!confirm('Êtes-vous sûr de vouloir vider votre panier ?')) return;
            
            $.post('{{ route("cart.clear") }}', {
                _token: '{{ csrf_token() }}'
            }, function() {
                location.reload();
            }).fail(function() {
                alert('Une erreur est survenue');
            });
        });
    });
</script>
@endpush