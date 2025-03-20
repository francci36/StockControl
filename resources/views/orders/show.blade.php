@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg">
        <!-- Titre de la page -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-blue-100 dark:bg-blue-900 rounded-t-lg">
            <h1 class="text-2xl font-semibold text-blue-700 dark:text-blue-400 flex items-center">
                <i class="fas fa-info-circle mr-2"></i> Détails de la commande #{{ $order->id }}
            </h1>
        </div>

        <!-- Détails de la commande -->
        <div class="px-6 py-4 space-y-6">
            <!-- Informations générales -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 dark:text-gray-600 font-medium">Fournisseur</label>
                    <p class="text-gray-800 dark:text-gray-200">{{ $order->supplier->name }}</p>
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-600 font-medium">Date de la commande</label>
                    <p class="text-gray-800 dark:text-gray-200">{{ $order->date->format('d/m/Y') }}</p>
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-600 font-medium">Statut</label>
                    <p class="text-gray-800 dark:text-gray-200">
                        <span class="px-2 py-1 text-sm rounded-full {{ $order->status === 'en attente' ? 'bg-yellow-100 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-100' : 'bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>
                </div>
                <div>
                    <label class="block text-gray-700 dark:text-gray-600 font-medium">Utilisateur</label>
                    <p class="text-gray-800 dark:text-gray-200">{{ $order->user->name }}</p>
                </div>
            </div>

            <!-- Liste des produits commandés -->
            <div>
                <h3 class="text-xl font-semibold text-blue-700 dark:text-blue-400 mb-4">Produits commandés</h3>
                <div class="overflow-x-auto">
                    <table class="table-auto w-full border-collapse border border-gray-200 dark:border-gray-600 text-sm">
                        <thead class="bg-blue-50 dark:bg-blue-900">
                            <tr>
                                <th class="border px-4 py-2 text-left text-gray-700 dark:text-gray-200 font-medium">Produit</th>
                                <th class="border px-4 py-2 text-left text-gray-700 dark:text-gray-200 font-medium">Quantité</th>
                                <th class="border px-4 py-2 text-left text-gray-700 dark:text-gray-200 font-medium">Prix unitaire</th>
                                <th class="border px-4 py-2 text-left text-gray-700 dark:text-gray-200 font-medium">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                            @foreach ($order->products as $product)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200">
                                <td class="border px-4 py-2 text-gray-800 dark:text-gray-200">{{ $product->name }}</td>
                                <td class="border px-4 py-2 text-gray-800 dark:text-gray-200">{{ $product->pivot->quantity }}</td>
                                <td class="border px-4 py-2 text-gray-800 dark:text-gray-200">{{ number_format($product->price, 2) }} €</td>
                                <td class="border px-4 py-2 text-gray-800 dark:text-gray-200">{{ number_format($product->price * $product->pivot->quantity, 2) }} €</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Bouton de retour -->
            <div class="flex justify-end">
                <a href="{{ route('orders.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg">
                    <i class="fas fa-arrow-left mr-2"></i> Retour
                </a>
            </div>
        </div>
    </div>
</div>
@endsection