@extends('layouts.app')

@section('title', 'Liste des Ventes')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
    <!-- En-tête -->
    <h2 class="text-2xl font-semibold mb-6 text-blue-600 dark:text-blue-400 flex items-center">
        <i class="fas fa-shopping-cart mr-2"></i> Liste des Ventes
    </h2>

    <!-- Table des ventes -->
    <div class="overflow-x-auto">
        <table class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-600">
            <thead>
                <tr class="bg-gray-100 dark:bg-gray-700">
                    <th class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">Produit</th>
                    <th class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">Quantité</th>
                    <th class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">Prix Total</th>
                    <th class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">Mode de Paiement</th>
                    <th class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sales as $sale)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200">
                    <!-- Produit vendu -->
                    <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                        @forelse($sale->products as $product)
                            {{ $product->name }}<br>
                        @empty
                            Produit non défini
                        @endforelse
                    </td>
                    <!-- Quantité -->
                    <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                        @foreach($sale->products as $product)
                            {{ $product->pivot->quantity }}<br>
                        @endforeach
                    </td>
                    <!-- Prix Total -->
                    <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                        {{ number_format($sale->total_price, 2, ',', ' ') }} €
                    </td>
                    <!-- Mode de Paiement -->
                    <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                        {{ ucfirst($sale->payment_mode) }}
                    </td>
                    <!-- Actions -->
                    <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                        <a href="{{ route('sales.show', $sale->id) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800">Afficher</a>
                        <a href="{{ route('sales.edit', $sale->id) }}" class="ml-2 text-yellow-600 dark:text-yellow-400 hover:text-yellow-800">Modifier</a>
                        <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" class="inline-block ml-2">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-2 text-center text-gray-500 dark:text-gray-400">
                        Aucune vente enregistrée.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-end">
        {{ $sales->links() }}
    </div>
</div>
@endsection
