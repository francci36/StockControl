@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Modifier la commande #{{ $order->id }}</h1>
        <form action="{{ route('orders.update', $order->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="supplier_id">Fournisseur</label>
                <select name="supplier_id" id="supplier_id" class="form-control">
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ $order->supplier_id == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" name="date" id="date" class="form-control" value="{{ $order->date->format('Y-m-d') }}">
            </div>

            <div class="form-group">
                <label for="status">Statut</label>
                <select name="status" id="status" class="form-control">
                    <option value="en cours" {{ $order->status == 'en cours' ? 'selected' : '' }}>En cours</option>
                    <option value="terminé" {{ $order->status == 'terminé' ? 'selected' : '' }}>Terminé</option>
                    <option value="annulé" {{ $order->status == 'annulé' ? 'selected' : '' }}>Annulé</option>
                </select>
            </div>

            <h3>Produits</h3>
            <div id="products">
                @foreach($order->items as $item)
                    <div class="product form-group">
                        <label for="product_id_{{ $item->id }}">Produit</label>
                        <select name="items[{{ $item->id }}][product_id]" id="product_id_{{ $item->id }}" class="form-control">
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>

                        <label for="quantity_{{ $item->id }}">Quantité</label>
                        <input type="number" name="items[{{ $item->id }}][quantity]" id="quantity_{{ $item->id }}" class="form-control" value="{{ $item->quantity }}">
                    </div>
                @endforeach
            </div>

            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fonction pour ajouter un nouveau produit
            function addProduct() {
                const productsDiv = document.getElementById('products');
                const index = productsDiv.children.length;
                const newProductHtml = `
                    <div class="product form-group">
                        <label for="new_product_id_${index}">Produit</label>
                        <select name="new_items[${index}][product_id]" id="new_product_id_${index}" class="form-control">
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>

                        <label for="new_quantity_${index}">Quantité</label>
                        <input type="number" name="new_items[${index}][quantity]" id="new_quantity_${index}" class="form-control" value="1">
                    </div>
                `;
                productsDiv.insertAdjacentHTML('beforeend', newProductHtml);
            }

            document.getElementById('add_product').addEventListener('click', addProduct);
        });
    </script>
@endsection
