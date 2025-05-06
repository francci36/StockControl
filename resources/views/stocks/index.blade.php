@extends('layouts.app')

@section('title', 'Gestion des stocks')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold text-blue-700 dark:text-blue-400 mb-6 flex items-center">
        <i class="fas fa-boxes mr-2"></i> Niveaux de stock
    </h2>

    <!-- Barre de recherche -->
    <div class="relative mb-4 w-full">
        <input type="text" id="live-search" 
               class="w-full px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 focus:ring-blue-300 dark:focus:ring-blue-500" 
               placeholder="🔍 Rechercher un produit...">
        <div id="search-suggestions" class="absolute z-10 mt-1 w-full bg-white dark:bg-gray-700 shadow-lg rounded-md hidden"></div>
    </div>

    <!-- Conteneur du tableau -->
    <div id="stocks-table-container">
        @include('stocks.partials.table', ['stocks' => $stocks])
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-end" id="pagination-links">
        {{ $stocks->links() }}
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const liveSearch = document.getElementById('live-search');
    const suggestionsDiv = document.getElementById('search-suggestions');
    let debounceTimer;

    // Recherche en temps réel avec debounce
    liveSearch.addEventListener('input', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            const searchTerm = this.value.trim();
            
            if (searchTerm.length > 0) {
                fetchStocks(searchTerm);
            } else {
                // Si le champ est vide, rechargez les données originales
                fetchStocks('');
            }
        }, 300);
    });

    function fetchStocks(searchTerm) {
        fetch(`{{ route('stocks.index') }}?search=${encodeURIComponent(searchTerm)}&ajax=1`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('stocks-table-container').innerHTML = data.html;
            document.getElementById('pagination-links').innerHTML = data.pagination;
            
            // Gestion des événements de pagination
            document.querySelectorAll('.pagination a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    fetchPage(this.href);
                });
            });
        });
    }

    function fetchPage(url) {
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('stocks-table-container').innerHTML = data.html;
            document.getElementById('pagination-links').innerHTML = data.pagination;
        });
    }
});
</script>
@endsection