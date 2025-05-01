@extends('layouts.app')

@section('title', 'Accueil Utilisateur')

@section('content')
<div class="container mx-auto py-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Carte Infos utilisateur -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <h2 class="text-xl font-semibold text-blue-700 dark:text-blue-400 flex items-center">
                <i class="fas fa-user-circle mr-2"></i> Bienvenue, {{ auth()->user()->name }}
            </h2>
            <p class="mt-2 text-gray-700 dark:text-gray-400">
                Accédez rapidement à vos commandes et transactions.
            </p>
        </div>

        <!-- Carte Statistiques -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-400">Résumé rapide</h3>
            <div class="flex justify-between mt-4">
                <div class="text-center">
                    <span class="text-3xl font-bold text-blue-500">{{ $totalOrders }}</span>
                    <p class="text-gray-600 dark:text-gray-400">Commandes</p>
                </div>
                <div class="text-center">
                    <span class="text-3xl font-bold text-green-500">{{ $totalTransactions }}</span>
                    <p class="text-gray-600 dark:text-gray-400">Transactions</p>
                </div>
                <div class="text-center">
                    <span class="text-3xl font-bold text-red-500">{{ $lowStockItems }}</span>
                    <p class="text-gray-600 dark:text-gray-400">Stocks faibles</p>
                </div>
            </div>
        </div>

        <!-- Section Actions rapides -->
        <div class="col-span-2">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <a href="{{ route('transactions.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-4 px-6 rounded-lg shadow-md flex items-center justify-between">
                    <span>Voir mes transactions</span>
                    <i class="fas fa-receipt text-xl"></i>
                </a>
                <a href="{{ route('orders.index') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-lg shadow-md flex items-center justify-between">
                    <span>Voir mes commandes</span>
                    <i class="fas fa-shopping-cart text-xl"></i>
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
