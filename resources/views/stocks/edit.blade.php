@extends('layouts.app')

@section('title', 'Modifier le stock')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold text-blue-700 dark:text-blue-400 mb-6 flex items-center">
        <i class="fas fa-edit mr-2"></i> Modifier le stock
    </h2>

    <!-- Formulaire de modification -->
    <form action="{{ route('stocks.update', $stock->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <!-- Produit -->
        <div>
            <label for="product" class="block text-gray-700 dark:text-gray-400 font-medium">Produit</label>
            <input type="text" id="product" value="{{ $stock->product->name ?? 'Produit inconnu' }}" 
                class="form-input mt-1 block w-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 border-gray-300 dark:border-gray-600 rounded-lg" readonly>
        </div>

        <!-- Quantité -->
        <div>
            <label for="quantity" class="block text-gray-700 dark:text-gray-400 font-medium">Quantité</label>
            <input type="number" name="quantity" id="quantity" value="{{ $stock->quantity }}" 
                class="form-input mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500" required>
        </div>

        <!-- Bouton de soumission -->
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                <i class="fas fa-save mr-2"></i> Enregistrer
            </button>
        </div>
    </form>
</div>
@endsection
