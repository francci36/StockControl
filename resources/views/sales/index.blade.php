@extends('layouts.app')

@section('title', 'Liste des Ventes')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
    <!-- En-tête -->
    <h2 class="text-2xl font-semibold mb-6 text-blue-600 dark:text-blue-400 flex items-center">
        <i class="fas fa-shopping-cart mr-2"></i> Liste des Ventes
    </h2>

    <!-- Barre de recherche et filtres -->
    <div class="mb-6">
        <form id="search-form" class="flex flex-col md:flex-row gap-4">
            <!-- Champ de recherche -->
            <div class="flex-grow">
                <input type="text" name="search" id="live-search" 
                       value="{{ request('search') }}"
                       class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-blue-300 dark:focus:ring-blue-500" 
                       placeholder="🔍 Rechercher par produit ou mode de paiement...">
            </div>
            
            <!-- Filtre par mode de paiement -->
            <div class="w-full md:w-64">
                <select name="payment_mode" id="payment-filter" class="w-full px-4 py-2 border rounded-md">
                    <option value="">Tous les modes</option>
                    @foreach($paymentModes as $key => $label)
                        <option value="{{ $key }}" {{ request('payment_mode') == $key ? 'selected' : '' }}>
                            {{ $label }}
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

    <!-- Table des ventes -->
    <div id="sales-table-container">
        @include('sales.partials.table', ['sales' => $sales])
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-end" id="pagination-links">
        {{ $sales->appends(request()->query())->links() }}
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const liveSearch = document.getElementById('live-search');
    const paymentFilter = document.getElementById('payment-filter');
    const resetFilters = document.getElementById('reset-filters');
    let debounceTimer;

    // Fonction pour charger les résultats
    function fetchSales() {
    const searchTerm = document.getElementById('live-search').value;
    const paymentMode = document.getElementById('payment-filter').value;

    const params = new URLSearchParams();
    if (searchTerm) params.append('search', searchTerm);
    if (paymentMode) params.append('payment_mode', paymentMode);
    params.append('ajax', '1');

    fetch(`/sales?${params.toString()}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('sales-table-container').innerHTML = data.html;
        document.getElementById('pagination-links').innerHTML = data.pagination;
    })
    .catch(error => console.error('Erreur:', error));
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
            document.getElementById('sales-table-container').innerHTML = data.html;
            document.getElementById('pagination-links').innerHTML = data.pagination;
        });
    }

    // Recherche en temps réel avec debounce
    liveSearch.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(fetchSales, 300);
    });

    // Filtre par mode de paiement
    paymentFilter.addEventListener('change', fetchSales);

    // Réinitialisation des filtres
    resetFilters.addEventListener('click', function() {
        liveSearch.value = '';
        paymentFilter.value = '';
        fetchSales();
    });

    // Chargement initial si filtres présents
    @if(request('search') || request('payment_mode'))
        fetchSales();
    @endif
});
</script>
@endsection