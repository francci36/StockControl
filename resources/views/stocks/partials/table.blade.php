<table class="table-auto w-full bg-gray-50 dark:bg-gray-700 border-collapse border border-gray-200 dark:border-gray-600 text-sm shadow-lg rounded-lg">
    <thead class="bg-blue-50 dark:bg-blue-900">
        <tr>
            <th class="border px-4 py-2 text-gray-700 dark:text-gray-200 font-medium text-left">Produit</th>
            <th class="border px-4 py-2 text-gray-700 dark:text-gray-200 font-medium text-left">Quantité en stock</th>
            <th class="border px-4 py-2 text-gray-700 dark:text-gray-200 font-medium text-left">Seuil de stock</th>
            <th class="border px-4 py-2 text-gray-700 dark:text-gray-200 font-medium text-left">Dernière mise à jour</th>
            <th class="border px-4 py-2 text-gray-700 dark:text-gray-200 font-medium text-left">Actions</th>
        </tr>
    </thead>
    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
        @forelse($stocks as $stock)
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200">
            <td class="border px-4 py-2 text-gray-800 dark:text-gray-200">
                {{ $stock->product->name ?? 'Produit inconnu' }}
            </td>
            <td class="border px-4 py-2 text-gray-800 dark:text-gray-200 font-semibold">
                @if($stock->quantity < 0)
                <span class="text-red-600 font-bold">{{ $stock->quantity }}</span>
                @else
                {{ $stock->quantity }}
                @endif
            </td>
            <td class="border px-4 py-2 text-gray-800 dark:text-gray-200">
                @if($stock->quantity <= $stock->product->stock_threshold)
                <span class="px-2 py-1 text-sm rounded-full bg-red-100 dark:bg-red-800 text-red-800 dark:text-red-100">
                    {{ $stock->product->stock_threshold ?? 5 }}
                </span>
                @else
                <span class="px-2 py-1 text-sm rounded-full bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100">
                    {{ $stock->product->stock_threshold }}
                </span>
                @endif
            </td>
            <td class="border px-4 py-2 text-gray-800 dark:text-gray-200">
                {{ $stock->updated_at ? $stock->updated_at->timezone('Europe/Paris')->format('d/m/Y H:i') : 'Jamais' }}
            </td>
            <td class="border px-4 py-2 text-gray-800 dark:text-gray-200">
                <a href="{{ route('stocks.edit', $stock->id) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800">
                    Modifier
                </a>
                <form action="{{ route('stocks.destroy', $stock->id) }}" method="POST" class="inline-block ml-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800">
                        Supprimer
                    </button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="border px-4 py-2 text-center text-gray-500 dark:text-gray-400">
                Aucun stock disponible.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>