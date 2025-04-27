@extends('layouts.app')

@section('title', 'Modifier la Vente')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold mb-6 text-blue-600 dark:text-blue-400">
        <i class="fas fa-edit mr-2"></i> Modifier la Vente
    </h2>

    <form action="{{ route('sales.update', $sale->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Mode de paiement -->
        <div class="mb-4">
            <label for="payment_mode" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Mode de paiement</label>
            <select name="payment_mode" id="payment_mode" class="w-full border border-gray-300 dark:border-gray-600 p-2 rounded-lg">
                <option value="cash" {{ $sale->payment_mode == 'cash' ? 'selected' : '' }}>Espèces</option>
                <option value="credit_card" {{ $sale->payment_mode == 'credit_card' ? 'selected' : '' }}>Carte de crédit</option>
                <option value="paypal" {{ $sale->payment_mode == 'paypal' ? 'selected' : '' }}>PayPal</option>
                <option value="stripe" {{ $sale->payment_mode == 'stripe' ? 'selected' : '' }}>Stripe</option>
                <option value="bank_transfer" {{ $sale->payment_mode == 'bank_transfer' ? 'selected' : '' }}>Virement bancaire</option>
            </select>
        </div>

        <!-- Liste des produits -->
        <h3 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-300">Produits dans cette vente :</h3>
        <table class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-600 mb-4">
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-700">
                    <th class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">Produit</th>
                    <th class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">Quantité</th>
                    <th class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">Prix Unitaire</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->products as $product)
                <tr>
                    <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ $product->name }}</td>
                    <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                        <input type="number" name="quantity[{{ $product->id }}]" value="{{ $product->pivot->quantity }}" class="w-full border border-gray-300 dark:border-gray-600 p-2 rounded-lg">
                    </td>
                    <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ number_format($product->pivot->unit_price, 2, ',', ' ') }} €</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Bouton de soumission -->
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Enregistrer les modifications
        </button>
    </form>
</div>
@endsection
