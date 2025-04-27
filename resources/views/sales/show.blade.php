@extends('layouts.app')

@section('title', 'Détails de la Vente')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold mb-6 text-blue-600 dark:text-blue-400 flex items-center">
        <i class="fas fa-receipt mr-2"></i> Détails de la Vente
    </h2>

    <!-- Informations de la vente -->
    <div class="mb-4">
        <p><strong>Mode de paiement :</strong> {{ ucfirst($sale->payment_mode) }}</p>
        <p><strong>Total :</strong> {{ number_format($sale->total_price, 2, ',', ' ') }} €</p>
        <p><strong>Status :</strong> {{ ucfirst($sale->status) }}</p>
        <p><strong>Date :</strong> {{ $sale->created_at->format('d/m/Y H:i') }}</p>
    </div>

    <!-- Liste des produits -->
    <h3 class="text-lg font-semibold mb-4 text-gray-700 dark:text-gray-300">Produits vendus :</h3>
    <table class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-600">
        <thead>
            <tr class="bg-gray-100 dark:bg-gray-700">
                <th class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">Produit</th>
                <th class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">Quantité</th>
                <th class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">Prix Unitaire</th>
                <th class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">Prix Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->products as $product)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200">
                <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ $product->name }}</td>
                <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ $product->pivot->quantity }}</td>
                <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ number_format($product->pivot->unit_price, 2, ',', ' ') }} €</td>
                <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">{{ number_format($product->pivot->total_price, 2, ',', ' ') }} €</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
