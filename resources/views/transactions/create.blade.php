@extends('layouts.app')

@section('title', 'Nouvelle transaction')

@section('content')
<div class="bg-white p-6 rounded-lg shadow">
    <h2 class="text-2xl font-semibold mb-6">Nouvelle transaction</h2>
    <form action="{{ route('transactions.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="product_id" class="block text-gray-700">Produit :</label>
            <select name="product_id" id="product_id" class="form-select mt-1 block w-full" required>
                @foreach($products as $product)
                <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                    {{ $product->name }} ({{ number_format($product->price, 2, ',', ' ') }} €)
                </option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="quantity" class="block text-gray-700">Quantité :</label>
            <input type="number" name="quantity" id="quantity" class="form-input mt-1 block w-full" required min="1">
        </div>
        <div class="mb-4">
            <label for="price" class="block text-gray-700">Prix Unitaire :</label>
            <input type="number" name="price" id="price" class="form-input mt-1 block w-full" step="0.01" required>
        </div>
        <div class="mb-4">
            <label for="total" class="block text-gray-700">Total :</label>
            <input type="number" name="total" id="total" class="form-input mt-1 block w-full" readonly>
        </div>
        <div class="mb-4">
            <label for="type" class="block text-gray-700">Type :</label>
            <select name="type" id="type" class="form-select mt-1 block w-full">
                <option value="entry">Entrée</option>
                <option value="exit">Sortie</option>
            </select>
        </div>
        <div class="flex items-center justify-end mt-4">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Enregistrer</button>
        </div>
    </form>
</div>

<script>
    // Calcul automatique du total
    document.getElementById('quantity').addEventListener('input', updateTotal);
    document.getElementById('price').addEventListener('input', updateTotal);

    function updateTotal() {
        const quantity = parseFloat(document.getElementById('quantity').value);
        const price = parseFloat(document.getElementById('price').value);
        const total = quantity * price;
        document.getElementById('total').value = isNaN(total) ? '' : total.toFixed(2);
    }

    // Mettre à jour le prix unitaire lors de la sélection du produit
    document.getElementById('product_id').addEventListener('change', function () {
        const selectedProduct = this.options[this.selectedIndex];
        const price = selectedProduct.getAttribute('data-price');
        document.getElementById('price').value = price;
        updateTotal();
    });

    // Initialiser le prix unitaire au chargement de la page
    document.addEventListener('DOMContentLoaded', function () {
        const selectedProduct = document.getElementById('product_id').options[0];
        const price = selectedProduct.getAttribute('data-price');
        document.getElementById('price').value = price;
        updateTotal();
    });
</script>
@endsection