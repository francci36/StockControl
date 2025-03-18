@extends('layouts.app')

@section('title', 'Transactions')

@section('content')
<div class="bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-semibold mb-6 text-blue-600 flex items-center">
        <i class="fas fa-list mr-2"></i> Historique des transactions
    </h2>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-gray-50 border rounded-lg shadow-lg">
            <thead class="bg-blue-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Produit</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Quantité</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Prix Unitaire</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Valeur Totale</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Date</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($transactions as $transaction)
                <tr class="hover:bg-gray-50 transition duration-200 ease-in-out">
                    <td class="px-6 py-4 whitespace-nowrap font-medium">{{ $transaction->product->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->quantity }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ number_format($transaction->price, 2, ',', ' ') }} €</td>
                    <td class="px-6 py-4 whitespace-nowrap font-semibold">{{ number_format($transaction->quantity * $transaction->price, 2, ',', ' ') }} €</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-sm rounded-full {{ $transaction->type === 'entry' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $transaction->type === 'entry' ? 'Entrée' : 'Sortie' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $transactions->links() }}
    </div>
</div>
@endsection
