@extends('layouts.app')

@section('title', 'Gestion des stocks')

@section('content')
<div class="bg-white p-6 rounded-lg shadow">
    <h2 class="text-2xl font-semibold mb-6">Niveaux de stock</h2>

    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead>
                <tr>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité en stock</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Seuil de stock</th>
                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dernière mise à jour</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($stocks as $stock)
                    <tr>
                        <!-- Nom du produit -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $stock->product->name ?? 'Produit inconnu' }}
                        </td>

                        <!-- Quantité en stock -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $stock->quantity }}
                        </td>

                        <!-- Seuil de stock (avec avertissement si nécessaire) -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($stock->quantity <= $stock->product->stock_threshold)
                                <span class="text-red-500">{{ $stock->product->stock_threshold }}</span>
                            @else
                                {{ $stock->product->stock_threshold }}
                            @endif
                        </td>

                        <!-- Dernière mise à jour -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $stock->updated_at ? $stock->updated_at->format('d/m/Y H:i') : 'Jamais' }}
                        </td>
                    </tr>
                @empty
                    <!-- Si la table stocks est vide -->
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            Aucun stock disponible.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $stocks->links() }}
    </div>
</div>
@endsection