@extends('layouts.app')

@section('title', 'Nouvelle Transaction')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
    <!-- En-tête -->
    <h2 class="text-2xl font-semibold mb-6 text-blue-600 dark:text-blue-400 flex items-center">
        <i class="fas fa-exchange-alt mr-2"></i> Nouvelle Transaction
    </h2>

    <!-- Selection du type de transaction (caché si on vient du panier) -->
    @if(!session('from_cart'))
    <div class="mb-4">
        <label for="transaction_type" class="block text-gray-800 dark:text-gray-600 font-medium">Type de transaction :</label>
        <select name="transaction_type" id="transaction_type" class="form-select mt-1 block w-full focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500 rounded-lg bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">
            <option value="exit">Vente</option>
            <option value="entry">Retour</option>
        </select>
    </div>
    @else
    <input type="hidden" name="transaction_type" id="transaction_type" value="exit">
    @endif

    <!-- Formulaire pour les ventes -->
    <form action="{{ route('transactions.store') }}" method="POST" class="space-y-4" id="saleForm">       
        @csrf
        <input type="hidden" name="type" value="exit">
        
        <!-- Section des produits -->
        <div class="overflow-x-auto">
            <table id="salesTable" class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-600">
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
                    @if(session('cart_items'))
                        @foreach(session('cart_items') as $id => $item)
                        <tr>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                <select name="product_id[]" class="product-select form-select w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg" required>
                                    <option value="{{ $id }}" data-price="{{ $item['price'] }}" selected>
                                        {{ $item['name'] }} ({{ number_format($item['price'], 2, ',', ' ') }} €)
                                    </option>
                                </select>
                            </td>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                <input type="number" name="quantity[]" class="quantity-input form-input w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg" 
                                       value="{{ $item['quantity'] }}" 
                                       required min="1">
                            </td>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                <input type="number" name="price[]" class="price-input form-input w-full bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg" 
                                       value="{{ $item['price'] }}" readonly>
                            </td>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                <input type="number" name="total[]" class="total-input form-input w-full bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg" 
                                       value="{{ $item['price'] * $item['quantity'] }}" readonly>
                            </td>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-center">
                                @if(!session('from_cart'))
                                <button type="button" class="remove-row bg-red-500 hover:bg-red-700 text-white p-2 rounded">Supprimer</button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <!-- Formulaire vide si pas de données de panier -->
                        <tr>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                <select name="product_id[]" class="product-select form-select w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg">
                                    @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock ? $product->stock->quantity : 0 }}">
                                    {{ $product->name }} ({{ number_format($product->price, 2, ',', ' ') }} €)
                                    </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                <input type="number" name="quantity[]" class="quantity-input form-input w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg" required min="1">
                            </td>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                <input type="number" name="price[]" class="price-input form-input w-full bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg" readonly>
                            </td>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                <input type="number" name="total[]" class="total-input form-input w-full bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg" readonly>
                            </td>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-center">
                                <button type="button" class="remove-row bg-red-500 hover:bg-red-700 text-white p-2 rounded">Supprimer</button>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Bouton pour ajouter une ligne (caché si on vient du panier) -->
        @if(!session('from_cart'))
        <button type="button" id="addRow" class="mt-4 bg-green-500 hover:bg-green-700 text-white p-2 rounded">
            Ajouter un produit
        </button>
        @endif

        <!-- Section des modes de paiement -->
        <div class="mt-6">
            <label for="payment_mode" class="block text-gray-800 dark:text-gray-600 font-medium">Mode de paiement :</label>
            <select name="payment_mode" id="payment_mode" class="form-select mt-1 block w-full focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500 rounded-lg bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200" required>
                <option value="cash">Espèces</option>
                <option value="credit_card">Carte de crédit</option>
                <option value="paypal">PayPal</option>
                <option value="stripe">Stripe</option>
                <option value="bank_transfer">Virement bancaire</option>
                <option value="check">Chèque</option>
            </select>
        </div>
        
        <!-- Logos des cartes bancaires -->
        <div id="creditCardLogos" style="display: none;" class="mt-4 flex items-center space-x-4">
            <img src="/images/visa.png" alt="Visa" class="h-8">
            <img src="/images/mastercard.png" alt="MasterCard" class="h-8">
            <img src="/images/amex.png" alt="American Express" class="h-8">
        </div>

        <!-- Champs pour Carte de Crédit -->
        <div id="creditCardFields" style="display: none;" class="mt-6">
            <label for="card_number" class="block text-gray-800 dark:text-gray-600 font-medium">Numéro de carte :</label>
            <input type="tel" name="card_number" id="card_number" class="form-input mt-1 w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg" maxlength="19" placeholder="XXXX XXXX XXXX XXXX" pattern="[0-9 ]+" inputmode="numeric">

            <label for="card_expiry" class="block text-gray-800 dark:text-gray-600 font-medium">Date d'expiration (MM/AA) :</label>
            <input type="text" name="card_expiry" id="card_expiry" class="form-input mt-1 w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg" placeholder="MM/AA" maxlength="5">

            <label for="card_cvv" class="block text-gray-800 dark:text-gray-600 font-medium mt-2">Code CVV :</label>
            <input type="tel" name="card_cvv" id="card_cvv" class="form-input mt-1 w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg" maxlength="3" placeholder="XXX">
        </div>

        <!-- Montant total -->
        <div class="mt-6">
            <label for="total_amount" class="block text-gray-800 dark:text-gray-600 font-medium">Montant total :</label>
            <input type="number" name="total_amount" id="total_amount" class="form-input mt-1 w-full bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg" 
                   value="{{ session('cart_total') ?? 0 }}" readonly>
        </div>

        <!-- Bouton de soumission -->
        <div class="flex items-center justify-end mt-4">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md">
                Finaliser la Vente
            </button>
        </div>
    </form>

    <!-- Formulaire pour les retours (caché) -->
    <form action="{{ route('transactions.storeReturn') }}" method="POST" class="space-y-4 hidden" id="returnForm">
        @csrf
        <input type="hidden" name="type" value="entry">
        
        <div class="mt-4">
            <label for="sale_id" class="block text-gray-800 dark:text-gray-600 font-medium">Référence de la vente originale :</label>
            <select name="sale_id" id="sale_id" class="form-select mt-1 w-full bg-white dark:bg-gray-700 border text-gray-800 dark:text-gray-200 rounded-lg" required>
                <option value="">Sélectionner la vente originale</option>
                @foreach($sales as $sale)
                <option value="{{ $sale->id }}" data-products="{{ $sale->products->toJson() }}">
                    Vente #{{ $sale->id }} - {{ $sale->created_at->format('d/m/Y H:i') }} - {{ number_format($sale->total_amount, 2, ',', ' ') }} €
                </option>
                @endforeach
            </select>
        </div>

        <div class="overflow-x-auto mt-4">
            <table id="returnTable" class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-600">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700">
                        <th class="px-4 py-2 border">Produit</th>
                        <th class="px-4 py-2 border">Quantité vendue</th>
                        <th class="px-4 py-2 border">Quantité à retourner</th>
                        <th class="px-4 py-2 border">Prix Unitaire</th>
                        <th class="px-4 py-2 border">Action</th>
                    </tr>
                </thead>
                <tbody id="returnProductsBody">
                    <!-- Rempli dynamiquement via JavaScript -->
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <label for="reason" class="block text-gray-800 dark:text-gray-600 font-medium">Raison du retour :</label>
            <textarea name="reason" id="reason" rows="3" class="form-textarea mt-1 w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg" required></textarea>
        </div>

        <div class="flex items-center justify-end mt-4">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md">
                Enregistrer le Retour
            </button>
        </div>
    </form>
</div>

<!-- JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Gestion du type de transaction
        const transactionType = document.getElementById('transaction_type');
        const saleForm = document.getElementById('saleForm');
        const returnForm = document.getElementById('returnForm');
        
        if(transactionType) {
            function updateTransactionType() {
                const isReturn = transactionType.value === 'entry';
                saleForm.classList.toggle('hidden', isReturn);
                returnForm.classList.toggle('hidden', !isReturn);
            }
            
            transactionType.addEventListener('change', updateTransactionType);
            updateTransactionType();
        }

        // Calcul automatique du total
        function calculateTotal() {
            let total = 0;
            document.querySelectorAll('.total-input').forEach(input => {
                total += parseFloat(input.value) || 0;
            });
            document.getElementById('total_amount').value = total.toFixed(2);
        }

        // Mise à jour des totaux ligne par ligne
        function updateRowTotal(row) {
            const quantity = parseFloat(row.querySelector('.quantity-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const total = quantity * price;
            row.querySelector('.total-input').value = total.toFixed(2);
            calculateTotal();
        }

        // Écouteurs d'événements pour les modifications
        document.querySelector('#salesTable').addEventListener('input', function(e) {
            if (e.target.classList.contains('quantity-input')) {
                updateRowTotal(e.target.closest('tr'));
            }
        });

        document.querySelector('#salesTable').addEventListener('change', function(e) {
            if (e.target.classList.contains('product-select')) {
                const selectedOption = e.target.options[e.target.selectedIndex];
                const price = selectedOption.getAttribute('data-price');
                const row = e.target.closest('tr');
                row.querySelector('.price-input').value = price;
                updateRowTotal(row);
            }
        });

        // Ajout d'une nouvelle ligne
        document.getElementById('addRow')?.addEventListener('click', function() {
            const table = document.querySelector('#salesTable tbody');
            const newRow = table.rows[0].cloneNode(true);
            
            // Réinitialiser les valeurs
            newRow.querySelectorAll('input').forEach(input => {
                if(!input.readOnly) input.value = '';
                if(input.classList.contains('total-input')) input.value = '0.00';
            });
            
            // Réinitialiser la sélection du produit
            const select = newRow.querySelector('.product-select');
            select.selectedIndex = 0;
            const selectedOption = select.options[select.selectedIndex];
            const price = selectedOption.getAttribute('data-price');
            newRow.querySelector('.price-input').value = price;
            
            table.appendChild(newRow);
        });

        // Suppression d'une ligne
        document.querySelector('#salesTable').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-row')) {
                const tbody = this.querySelector('tbody');
                if (tbody.rows.length > 1) {
                    e.target.closest('tr').remove();
                    calculateTotal();
                } else {
                    alert('Vous ne pouvez pas supprimer la dernière ligne.');
                }
            }
        });

        // Gestion des modes de paiement
        document.getElementById('payment_mode').addEventListener('change', function() {
            const mode = this.value;
            const creditCardFields = document.getElementById('creditCardFields');
            const creditCardLogos = document.getElementById('creditCardLogos');
            
            creditCardFields.style.display = mode === 'credit_card' ? 'block' : 'none';
            creditCardLogos.style.display = mode === 'credit_card' ? 'flex' : 'none';
        });

        // Initialisation des valeurs si on vient du panier
        if(document.querySelector('#salesTable tbody tr')) {
            document.querySelectorAll('#salesTable tbody tr').forEach(row => {
                updateRowTotal(row);
            });
        }

        // Gestion des retours
        document.getElementById('sale_id')?.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const products = JSON.parse(selectedOption.dataset.products || '[]');
            const tbody = document.getElementById('returnProductsBody');
            
            tbody.innerHTML = '';
            
            products.forEach(product => {
                const row = document.createElement('tr');
                row.className = 'border-b border-gray-300 dark:border-gray-600';
                row.innerHTML = `
                    <td class="px-4 py-2">
                        ${product.name}
                        <input type="hidden" name="product_id[]" value="${product.id}">
                    </td>
                    <td class="px-4 py-2">${product.pivot.quantity}</td>
                    <td class="px-4 py-2">
                        <input type="number" name="quantity[]" min="0" max="${product.pivot.quantity}" 
                            value="0" class="w-full rounded-lg border-gray-300 return-quantity">
                    </td>
                    <td class="px-4 py-2">
                        <input type="number" name="price[]" value="${product.pivot.unit_price}" 
                            class="w-full rounded-lg border-gray-300" readonly>
                    </td>
                    <td class="px-4 py-2 text-center">
                        <button type="button" class="remove-return-row bg-red-500 hover:bg-red-700 text-white p-2 rounded">
                            Supprimer
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        });

        // Calcul du total pour les retours
        document.getElementById('returnProductsBody')?.addEventListener('input', function(e) {
            if (e.target.classList.contains('return-quantity')) {
                calculateReturnTotal();
            }
        });

        function calculateReturnTotal() {
            let total = 0;
            document.querySelectorAll('#returnProductsBody tr').forEach(row => {
                const quantity = parseFloat(row.querySelector('.return-quantity').value) || 0;
                const price = parseFloat(row.querySelector('input[name="price[]"]').value) || 0;
                total += quantity * price;
            });
            document.getElementById('total_amount').value = total.toFixed(2);
        }
    });
</script>
@endsection