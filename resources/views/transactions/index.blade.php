@extends('layouts.app')

@section('title', 'Transactions de Sortie')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-semibold mb-6 text-blue-600 dark:text-blue-400 flex items-center">
        <i class="fas fa-exchange-alt mr-2"></i> Historique des Transactions 
    </h2>

    <!-- Filtres -->
    <div class="mb-4 flex space-x-4">
        <a href="{{ route('transactions.index', ['type' => 'exit']) }}" 
           class="px-4 py-2 rounded-lg {{ request('type') === 'exit' ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700' }}">
           Ventes
        </a>
        <a href="{{ route('transactions.index', ['type' => 'entry']) }}" 
           class="px-4 py-2 rounded-lg {{ request('type') === 'entry' ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700' }}">
           Retours
        </a>
        <a href="{{ route('transactions.index') }}" 
           class="px-4 py-2 rounded-lg {{ !request('type') ? 'bg-blue-500 text-white' : 'bg-gray-200 dark:bg-gray-700' }}">
           Toutes
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full bg-gray-50 dark:bg-gray-700 border rounded-lg shadow-lg">
            <thead class="bg-blue-100 dark:bg-blue-900">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200 uppercase tracking-wider">Produit</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200 uppercase tracking-wider">Quantité</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200 uppercase tracking-wider">Prix Unitaire</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200 uppercase tracking-wider">Valeur Totale</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200 uppercase tracking-wider">Raison</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200 uppercase tracking-wider">Date</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($transactions as $transaction)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200 ease-in-out">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                        {{ $transaction->id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800 dark:text-gray-200">
                        {{ $transaction->product ? $transaction->product->name : 'Produit inconnu' }}
                    </td>
                    
                    <td class="px-6 py-4 whitespace-nowrap {{ $transaction->type === 'entry' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $transaction->type === 'entry' ? '+' : '-' }}{{ abs($transaction->quantity) }}
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-200">
                        {{ number_format($transaction->price, 2, ',', ' ') }} €
                    </td>
                    
                    <td class="px-6 py-4 whitespace-nowrap font-semibold {{ $transaction->type === 'entry' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $transaction->type === 'entry' ? '+' : '-' }}{{ number_format(abs($transaction->quantity * $transaction->price), 2, ',', ' ') }} €
                    </td>
                    
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-sm rounded-full 
                            {{ $transaction->type === 'entry' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' }}">
                            {{ $transaction->type === 'entry' ? 'Retour' : 'Vente' }}
                        </span>
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-200">
                        {{ $transaction->reason ?? 'Non spécifiée' }}
                    </td>
                    
                    <td class="px-6 py-4 whitespace-nowrap text-gray-800 dark:text-gray-200">
                        {{ $transaction->created_at ? $transaction->created_at->format('d/m/Y H:i') : 'Date inconnue' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                        Aucune transaction trouvée.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $transactions->links() }}
    </div>
</div>
@endsection