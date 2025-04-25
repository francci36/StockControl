@extends('layouts.app')

@section('title', 'Nouvelle transaction')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
    <!-- En-tête -->
    <h2 class="text-2xl font-semibold mb-6 text-blue-600 dark:text-blue-400 flex items-center">
        <i class="fas fa-coins mr-2"></i> Nouvelle transaction
    </h2>

    <!-- Formulaire -->
    <form action="{{ route('transactions.store') }}" method="POST" class="space-y-4" id="transactionForm">
        @csrf
        <!-- Section de la table -->
        <div class="overflow-x-auto">
            <table id="transactionTable" class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-600">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700">
                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">Produit</th>
                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">Quantité</th>
                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">Prix Unitaire</th>
                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">Total</th>
                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                            <select name="product_id[]" class="form-select w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg">
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock->quantity }}">
                                        {{ $product->name }} ({{ number_format($product->price, 2, ',', ' ') }} €)
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                            <input type="number" name="quantity[]" class="form-input w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg" required min="1">
                        </td>
                        <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                            <input type="number" name="price[]" class="form-input w-full bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg" readonly>
                        </td>
                        <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                            <input type="number" name="total[]" class="form-input w-full bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg" readonly>
                        </td>
                        <td class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-center">
                            <button type="button" class="remove-row bg-red-500 hover:bg-red-700 text-white p-2 rounded">Supprimer</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Bouton pour ajouter une ligne -->
        <button type="button" id="addRow" class="mt-4 bg-green-500 hover:bg-green-700 text-white p-2 rounded">
            Ajouter une ligne
        </button>

        <!-- Type de transaction -->
        <div class="mt-6">
            <label for="type" class="block text-gray-800 dark:text-gray-600 font-medium">Type :</label>
            <select name="type" id="type" class="form-select mt-1 block w-full focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500 rounded-lg bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">
                <option value="entry">Entrée</option>
                <option value="exit">Sortie</option>
            </select>
        </div>

        <!-- Bouton de soumission -->
        <div class="flex items-center justify-end mt-4">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md">
                <i class="fas fa-save mr-2"></i> Enregistrer
            </button>
        </div>
    </form>
</div>

<!-- JavaScript -->
<script>
    // Ajout d'une nouvelle ligne au tableau
    document.getElementById('addRow').addEventListener('click', function () {
        const table = document.getElementById('transactionTable').getElementsByTagName('tbody')[0];
        const newRow = table.rows[0].cloneNode(true);
        newRow.querySelectorAll('input').forEach(input => input.value = '');
        table.appendChild(newRow);
    });

    // Suppression d'une ligne
    document.getElementById('transactionTable').addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            e.target.closest('tr').remove();
        }
    });

    // Validation de la quantité et mise à jour du total
    document.querySelector('#transactionTable').addEventListener('input', function () {
        document.querySelectorAll('#transactionTable tbody tr').forEach(row => {
            const quantityInput = row.querySelector('input[name="quantity[]"]');
            const productSelect = row.querySelector('select[name="product_id[]"]');
            const selectedOption = productSelect.options[productSelect.selectedIndex];
            const availableStock = parseInt(selectedOption.getAttribute('data-stock'));
            const quantity = parseInt(quantityInput.value);

            // Validation de la quantité
            if (quantity > availableStock) {
                alert(`Quantité demandée (${quantity}) dépasse le stock disponible (${availableStock}).`);
                quantityInput.value = ''; // Réinitialise la quantité
            }

            // Calcul du total
            const price = parseFloat(row.querySelector('input[name="price[]"]').value);
            const total = quantity * price;
            row.querySelector('input[name="total[]"]').value = isNaN(total) ? '' : total.toFixed(2);
        });
    });

    // Mise à jour des informations du produit
    document.querySelector('#transactionTable').addEventListener('change', function (e) {
        if (e.target.tagName === 'SELECT') {
            const selectedOption = e.target.options[e.target.selectedIndex];
            const price = selectedOption.getAttribute('data-price');
            const stock = selectedOption.getAttribute('data-stock');
            const row = e.target.closest('tr');
            row.querySelector('input[name="price[]"]').value = price;
            row.querySelector('input[name="quantity[]"]').setAttribute('max', stock);
            row.querySelector('input[name="quantity[]"]').dispatchEvent(new Event('input'));
        }
    });

    // Initialisation de la première ligne
    document.addEventListener('DOMContentLoaded', function () {
        const firstRow = document.querySelector('#transactionTable tbody tr');
        const firstOption = firstRow.querySelector('select[name="product_id[]"]').options[0];
        const price = firstOption.getAttribute('data-price');
        const stock = firstOption.getAttribute('data-stock');
        firstRow.querySelector('input[name="price[]"]').value = price;
        firstRow.querySelector('input[name="quantity[]"]').setAttribute('max', stock);
    });
</script>
@endsection
