@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-semibold text-blue-700 dark:text-blue-400 mb-6">Modifier la commande #{{ $order->id }}</h1>
        <form action="{{ route('orders.update', $order->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group mb-4">
                <label for="supplier_id" class="block text-gray-700 dark:text-gray-600 font-medium">Fournisseur</label>
                <select name="supplier_id" id="supplier_id" class="form-control mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500">
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ $order->supplier_id == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-4">
                <label for="date" class="block text-gray-700 dark:text-gray-600 font-medium">Date</label>
                <input type="date" name="date" id="date" class="form-control mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500" value="{{ $order->date->format('Y-m-d') }}">
            </div>

            <div class="form-group mb-4">
                <label for="status" class="block text-gray-700 dark:text-gray-600 font-medium">Statut</label>
                <select name="status" id="status" class="form-control mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500">
                    <option value="en cours" {{ $order->status == 'en cours' ? 'selected' : '' }}>En cours</option>
                    <option value="terminé" {{ $order->status == 'terminé' ? 'selected' : '' }}>Terminé</option>
                    <option value="annulé" {{ $order->status == 'annulé' ? 'selected' : '' }}>Annulé</option>
                </select>
            </div>

            <h3 class="text-xl font-semibold text-blue-700 dark:text-blue-400 mb-4">Produits</h3>
            <div id="products" class="space-y-4">
                @foreach($order->items as $item)
                    <div class="product form-group">
                        <label for="product_id_{{ $item->id }}" class="block text-gray-700 dark:text-gray-600 font-medium">Produit</label>
                        <select name="items[{{ $item->id }}][product_id]" id="product_id_{{ $item->id }}" class="form-control mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500">
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>

                        <label for="quantity_{{ $item->id }}" class="block text-gray-700 dark:text-gray-600 font-medium">Quantité</label>
                        <input type="number" name="items[{{ $item->id }}][quantity]" id="quantity_{{ $item->id }}" class="form-control mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500" value="{{ $item->quantity }}">
                    </div>
                @endforeach
            </div>

            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg mt-6">
                Enregistrer
            </button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fonction pour ajouter un nouveau produit
        function addProduct() {
            const productsDiv = document.getElementById('products');
            const index = productsDiv.children.length;
            const newProductHtml = `
                <div class="product form-group">
                    <label for="new_product_id_${index}" class="block text-gray-700 dark:text-gray-200 font-medium">Produit</label>
                    <select name="new_items[${index}][product_id]" id="new_product_id_${index}" class="form-control mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500">
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>

                    <label for="new_quantity_${index}" class="block text-gray-700 dark:text-gray-200 font-medium">Quantité</label>
                    <input type="number" name="new_items[${index}][quantity]" id="new_quantity_${index}" class="form-control mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500" value="1">
                </div>
            `;
            productsDiv.insertAdjacentHTML('beforeend', newProductHtml);
        }

        document.getElementById('add_product').addEventListener('click', addProduct);
    });
</script>
@endsection