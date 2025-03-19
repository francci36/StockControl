@extends('layouts.app')

@section('title', 'Nouvelle transaction')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-semibold mb-6 text-blue-600 dark:text-blue-400 flex items-center">
        <i class="fas fa-coins mr-2"></i> Nouvelle transaction
    </h2>
    <form action="{{ route('transactions.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label for="product_id" class="block text-gray-700 dark:text-gray-200 font-medium">Produit :</label>
            <select name="product_id" id="product_id" class="form-select mt-1 block w-full focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500 rounded-lg bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200" required>
                @foreach($products as $product)
                <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                    {{ $product->name }} ({{ number_format($product->price, 2, ',', ' ') }} €)
                </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="quantity" class="block text-gray-700 dark:text-gray-200 font-medium">Quantité :</label>
            <input type="number" name="quantity" id="quantity" class="form-input mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200" required min="1">
        </div>
        <div>
            <label for="price" class="block text-gray-700 dark:text-gray-200 font-medium">Prix Unitaire :</label>
            <input type="number" name="price" id="price" class="form-input mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200" step="0.01" required>
        </div>
        <div>
            <label for="total" class="block text-gray-700 dark:text-gray-200 font-medium">Total :</label>
            <input type="number" name="total" id="total" class="form-input mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500 bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-200" readonly>
        </div>
        <div>
            <label for="type" class="block text-gray-700 dark:text-gray-200 font-medium">Type :</label>
            <select name="type" id="type" class="form-select mt-1 block w-full focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500 rounded-lg bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200">
                <option value="entry">Entrée</option>
                <option value="exit">Sortie</option>
            </select>
        </div>
        <div class="flex items-center justify-end mt-4">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md">
                <i class="fas fa-save mr-2"></i> Enregistrer
            </button>
        </div>
    </form>
</div>

<script>
    document.getElementById('quantity').addEventListener('input', updateTotal);
    document.getElementById('price').addEventListener('input', updateTotal);

    function updateTotal() {
        const quantity = parseFloat(document.getElementById('quantity').value);
        const price = parseFloat(document.getElementById('price').value);
        const total = quantity * price;
        document.getElementById('total').value = isNaN(total) ? '' : total.toFixed(2);
    }

    document.getElementById('product_id').addEventListener('change', function () {
        const selectedProduct = this.options[this.selectedIndex];
        const price = selectedProduct.getAttribute('data-price');
        document.getElementById('price').value = price;
        updateTotal();
    });

    document.addEventListener('DOMContentLoaded', function () {
        const selectedProduct = document.getElementById('product_id').options[0];
        const price = selectedProduct.getAttribute('data-price');
        document.getElementById('price').value = price;
        updateTotal();
    });
</script>
@endsection