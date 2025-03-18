@extends('layouts.app')

@section('title', 'Gestion des stocks')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold text-blue-700 mb-6 flex items-center">
        <i class="fas fa-boxes mr-2"></i> Niveaux de stock
    </h2>

    <div class="overflow-x-auto">
        <table class="table-auto w-full bg-gray-50 border-collapse border border-gray-200 text-sm shadow-lg rounded-lg">
            <thead class="bg-blue-50">
                <tr>
                    <th class="border px-4 py-2 text-gray-700 font-medium text-left">Produit</th>
                    <th class="border px-4 py-2 text-gray-700 font-medium text-left">Quantité en stock</th>
                    <th class="border px-4 py-2 text-gray-700 font-medium text-left">Seuil de stock</th>
                    <th class="border px-4 py-2 text-gray-700 font-medium text-left">Dernière mise à jour</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($stocks as $stock)
                <tr class="hover:bg-gray-50 transition duration-200">
                    <!-- Nom du produit -->
                    <td class="border px-4 py-2 text-gray-800">
                        {{ $stock->product->name ?? 'Produit inconnu' }}
                    </td>

                    <!-- Quantité en stock -->
                    <td class="border px-4 py-2 text-gray-800 font-semibold">
                        {{ $stock->quantity }}
                    </td>

                    <!-- Seuil de stock (avec avertissement si nécessaire) -->
                    <td class="border px-4 py-2 text-gray-800">
                        @if($stock->quantity <= $stock->product->stock_threshold)
                        <span class="px-2 py-1 text-sm rounded-full bg-red-100 text-red-800">
                            {{ $stock->product->stock_threshold ?? 5 }}
                        </span>
                        @else
                        <span class="px-2 py-1 text-sm rounded-full bg-green-100 text-green-800">
                            {{ $stock->product->stock_threshold }}
                        </span>
                        @endif
                    </td>

                    <!-- Dernière mise à jour -->
                    <td class="border px-4 py-2 text-gray-800">
                        {{ $stock->updated_at ? $stock->updated_at->format('d/m/Y H:i') : 'Jamais' }}
                    </td>
                </tr>
                @empty
                <!-- Si aucun stock n'est disponible -->
                <tr>
                    <td colspan="4" class="border px-4 py-2 text-center text-gray-500">
                        Aucun stock disponible.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-end">
        {{ $stocks->links() }}
    </div>
</div>
@endsection
