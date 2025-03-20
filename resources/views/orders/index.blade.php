@extends('layouts.app')

@section('title', 'Liste des commandes')

@section('content')
<div class="container mx-auto py-6">
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg">
        <!-- Titre de la page -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-blue-100 dark:bg-blue-900 rounded-t-lg">
            <h1 class="text-2xl font-semibold text-blue-700 dark:text-blue-400 flex items-center">
                <i class="fas fa-list-alt mr-2"></i> Liste des commandes
            </h1>
        </div>

        <!-- Tableau des commandes -->
        <div class="px-6 py-4">
            <div class="overflow-x-auto">
                <table class="table-auto w-full border-collapse border border-gray-200 dark:border-gray-600 text-sm">
                    <thead class="bg-blue-50 dark:bg-blue-900">
                        <tr>
                            <th class="border px-4 py-2 text-left text-gray-700 dark:text-gray-200 font-medium">ID</th>
                            <th class="border px-4 py-2 text-left text-gray-700 dark:text-gray-200 font-medium">Fournisseur</th>
                            <th class="border px-4 py-2 text-left text-gray-700 dark:text-gray-200 font-medium">Date</th>
                            <th class="border px-4 py-2 text-left text-gray-700 dark:text-gray-200 font-medium">Statut</th>
                            <th class="border px-4 py-2 text-left text-gray-700 dark:text-gray-200 font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                        @foreach ($orders as $order)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-200">
                            <td class="border px-4 py-2 text-gray-800 dark:text-gray-200">{{ $order->id }}</td>
                            <td class="border px-4 py-2 text-gray-800 dark:text-gray-200">
                                {{ $order->supplier ? $order->supplier->name : 'Aucun fournisseur' }}
                            </td>
                            <td class="border px-4 py-2 text-gray-800 dark:text-gray-200">{{ $order->date->format('d/m/Y') }}</td>
                            <td class="border px-4 py-2">
                                <span class="px-2 py-1 text-sm rounded-full {{ $order->status === 'en attente' ? 'bg-yellow-100 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-100' : 'bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="border px-4 py-2">
                                <div class="flex gap-2">
                                    <!-- Lien pour voir les détails de la commande -->
                                    <a href="{{ route('orders.show', $order->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white text-sm font-bold py-1 px-3 rounded">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <!-- Formulaire de mise à jour du statut -->
                                    <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="form-select text-sm rounded-lg border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500">
                                            <option value="en attente" {{ $order->status === 'en attente' ? 'selected' : '' }}>En attente</option>
                                            <option value="arrivé" {{ $order->status === 'arrivé' ? 'selected' : '' }}>Arrivé</option>
                                        </select>
                                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white text-sm font-bold py-1 px-3 rounded">
                                            <i class="fas fa-sync-alt"></i>
                                        </button>
                                    </form>

                                    <!-- Formulaire de suppression -->
                                    <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white text-sm font-bold py-1 px-3 rounded" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette commande ?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4">
            <div class="flex justify-end">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection