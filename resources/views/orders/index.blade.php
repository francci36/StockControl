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

        <!-- Barre de recherche et filtres -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <form id="search-form" class="flex flex-col md:flex-row gap-4">
                <!-- Champ de recherche -->
                <div class="flex-grow">
                    <input type="text" name="search" id="live-search" 
                           value="{{ request('search') }}"
                           class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-blue-300 dark:focus:ring-blue-500" 
                           placeholder="🔍 Rechercher par ID ou nom de fournisseur...">
                </div>
                
                <!-- Filtre par fournisseur -->
                <div class="w-full md:w-64">
                    <select name="supplier_id" id="supplier-filter" class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-blue-300 dark:focus:ring-blue-500">
                        <option value="">Tous les fournisseurs</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Bouton de réinitialisation -->
                <button type="button" id="reset-filters" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                    Réinitialiser
                </button>
            </form>
        </div>

        <!-- Tableau des commandes -->
        <div class="px-6 py-4">
            <div id="orders-table-container">
                @include('orders.partials.table', ['orders' => $orders])
            </div>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4">
            <div id="pagination-links" class="flex justify-end">
                {{ $orders->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const liveSearch = document.getElementById('live-search');
    const supplierFilter = document.getElementById('supplier-filter');
    const resetFilters = document.getElementById('reset-filters');
    let debounceTimer;

    // Fonction pour charger les résultats
    function fetchOrders() {
        const searchTerm = liveSearch.value;
        const supplierId = supplierFilter.value;
        
        const params = new URLSearchParams();
        if (searchTerm) params.append('search', searchTerm);
        if (supplierId) params.append('supplier_id', supplierId);
        params.append('ajax', '1');

        fetch(`{{ route('orders.index') }}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Erreur réseau');
            return response.json();
        })
        .then(data => {
            document.getElementById('orders-table-container').innerHTML = data.html;
            document.getElementById('pagination-links').innerHTML = data.pagination;
            
            // Réattacher les événements de pagination
            document.querySelectorAll('.pagination a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    fetchPage(this.href);
                });
            });
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
    }

    // Fonction pour charger une page spécifique
    function fetchPage(url) {
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('orders-table-container').innerHTML = data.html;
            document.getElementById('pagination-links').innerHTML = data.pagination;
        });
    }

    // Recherche en temps réel avec debounce
    liveSearch.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(fetchOrders, 300);
    });

    // Filtre par fournisseur
    supplierFilter.addEventListener('change', fetchOrders);

    // Réinitialisation des filtres
    resetFilters.addEventListener('click', function() {
        liveSearch.value = '';
        supplierFilter.value = '';
        fetchOrders();
    });

    // Chargement initial si filtres présents
    @if(request('search') || request('supplier_id'))
        fetchOrders();
    @endif
});
</script>
@endsection