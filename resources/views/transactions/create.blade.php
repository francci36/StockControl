@extends('layouts.app')

@section('title', 'Nouvelle Transaction')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
    <!-- En-tête -->
    <h2 class="text-2xl font-semibold mb-6 text-blue-600 dark:text-blue-400 flex items-center">
        <i class="fas fa-exchange-alt mr-2"></i> 
        @if(isset($from_cart) && $from_cart)
            Finalisation de commande
        @else
            Nouvelle Transaction
        @endif
    </h2>

    <!-- Avertissement pour le mode panier -->
    @if(isset($from_cart) && $from_cart)
    <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
        <p class="text-blue-800 dark:text-blue-200">
            <i class="fas fa-info-circle mr-2"></i>
            Vous êtes en train de finaliser votre commande. Le panier sera vidé après paiement.
        </p>
    </div>
    @endif

    <!-- Selection du type de transaction -->
    <div class="mb-4">
        <label for="transaction_type" class="block text-gray-800 dark:text-gray-600 font-medium">Type de transaction :</label>
        <select name="transaction_type" id="transaction_type" class="form-select mt-1 block w-full focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500 rounded-lg bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200" @if(isset($from_cart) && $from_cart) disabled @endif>
            <option value="exit" selected>Vente</option>
            <option value="entry">Retour</option>
        </select>
    </div>

    <!-- Formulaire pour les ventes -->
    <form action="{{ route('transactions.store') }}" method="POST" class="space-y-4" id="saleForm">
        @csrf
        <input type="hidden" name="type" value="exit">
        @if(isset($from_cart) && $from_cart)
            <input type="hidden" name="from_cart" value="1">
        @endif
        
        <!-- Section des produits -->
        <div class="overflow-x-auto">
            <table id="salesTable" class="table-auto w-full border-collapse border border-gray-300 dark:border-gray-600">
                <thead>
                    <tr class="bg-gray-100 dark:bg-gray-700">
                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">Produit</th>
                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">Quantité</th>
                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">Prix Unitaire</th>
                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">Total</th>
                        @if(!isset($from_cart) || !$from_cart)
                        <th class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                @if(session('from_cart'))
                <!-- Mode Panier -->
                     @foreach(session('cart_items') as $item)
                            <tr>
                                <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                    <select name="product_id[]" class="product-select form-select w-full" required>
                                        <option value="{{ $item['id'] }}" 
                                                data-price="{{ $item['price'] }}" 
                                                data-stock="{{ $item['stock'] }}" 
                                                selected>
                                            {{ $item['name'] }} ({{ number_format($item['price'], 2) }} €)
                                        </option>
                                    </select>
                                </td>
                                <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                    <input type="number" name="quantity[]" 
                                        value="{{ $item['quantity'] }}" 
                                        class="quantity-input form-input w-full" 
                                        required min="1" max="{{ $item['stock'] }}">
                                </td>
                                <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                    <input type="number" name="price[]" 
                                        value="{{ $item['price'] }}" 
                                        class="price-input form-input w-full bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg" 
                                        readonly>
                                </td>
                                <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                    <input type="number" name="total[]" 
                                        value="{{ $item['price'] * $item['quantity'] }}" 
                                        class="total-input form-input w-full bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg" 
                                        readonly>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <!-- Mode Normal - Formulaire vide -->
                        <tr>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                <select name="product_id[]" class="product-select form-select w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg">
                                    <option value="">Sélectionner un produit</option>
                                    @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock ? $product->stock->quantity : 0 }}">
                                        {{ $product->name }} ({{ number_format($product->price, 2, ',', ' ') }} €)
                                    </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-4 py-2 border border-gray-300 dark:border-gray-600">
                                <input type="number" name="quantity[]" class="quantity-input form-input w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg" min="1">
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

        <!-- Bouton pour ajouter une ligne (uniquement en mode normal) -->
        @if(!isset($from_cart) || !$from_cart)
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

        <!-- Champs pour Carte de Crédit -->
        <div id="creditCardFields" style="display: none;" class="mt-6 space-y-4">
            <div>
                <label for="card_number" class="block text-gray-800 dark:text-gray-600 font-medium">Numéro de carte :</label>
                <input type="tel" name="card_number" id="card_number" class="form-input mt-1 w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg" maxlength="19" placeholder="XXXX XXXX XXXX XXXX" pattern="[0-9 ]+" inputmode="numeric">
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="card_expiry" class="block text-gray-800 dark:text-gray-600 font-medium">Date d'expiration :</label>
                    <input type="text" name="card_expiry" id="card_expiry" class="form-input mt-1 w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg" placeholder="MM/AA" maxlength="5">
                </div>
                <div>
                    <label for="card_cvv" class="block text-gray-800 dark:text-gray-600 font-medium">Code CVV :</label>
                    <input type="tel" name="card_cvv" id="card_cvv" class="form-input mt-1 w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg" maxlength="3" placeholder="XXX">
                </div>
            </div>
        </div>

        <!-- Montant total -->
        <div class="mt-6">
            <label for="total_amount" class="block text-gray-800 dark:text-gray-600 font-medium">Montant total :</label>
            <input type="number" name="total_amount" id="total_amount" class="form-input mt-1 w-full bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg" 
                   value="{{ isset($from_cart) && $from_cart ? number_format($cart_total, 2, '.', '') : 0 }}" readonly>
        </div>

        <!-- Bouton de soumission -->
        <div class="flex items-center justify-end mt-4 space-x-4">
            <a href="{{ isset($from_cart) && $from_cart ? route('cart.index') : route('transactions.index') }}" class="btn btn-secondary">
                Annuler
            </a>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md">
                {{ isset($from_cart) && $from_cart ? 'Payer et finaliser' : 'Enregistrer' }}
            </button>
        </div>
    </form>

    <!-- Formulaire pour les retours -->
    <form action="{{ route('transactions.storeReturn') }}" method="POST" class="space-y-4 hidden" id="returnForm">
        @csrf
        <input type="hidden" name="type" value="entry">
        
        <!-- Référence de la vente originale -->
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

        <!-- Produits à retourner -->
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

        <!-- Raison du retour -->
        <div class="mt-4">
            <label for="reason" class="block text-gray-800 dark:text-gray-600 font-medium">Raison du retour :</label>
            <textarea name="reason" id="reason" rows="3" class="form-textarea mt-1 w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg" required></textarea>
        </div>

        <!-- Bouton de soumission -->
        <div class="flex items-center justify-end mt-4">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md">
                Enregistrer le Retour
            </button>
        </div>
    </form>
</div>

<!-- JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Récupération des éléments DOM
    const transactionType = document.getElementById('transaction_type');
    const saleForm = document.getElementById('saleForm');
    const returnForm = document.getElementById('returnForm');
    const paymentMode = document.getElementById('payment_mode');
    const creditCardFields = document.getElementById('creditCardFields');

    // Initialisation si on vient du panier
    @if(session('from_cart'))
        // Désactiver le changement de type de transaction
        if (transactionType) {
            transactionType.disabled = true;
            transactionType.value = 'exit'; // Forcer le type "Vente"
        }
        
        // Pré-remplir le total si disponible
        const totalAmountInput = document.getElementById('total_amount');
        if (totalAmountInput) {
            totalAmountInput.value = {{ session('cart_total', 0) }};
        }
    @endif

    // Gestion du type de transaction
    function updateTransactionType() {
        if (!transactionType || !saleForm || !returnForm) return;
        
        const isReturn = transactionType.value === 'entry';
        saleForm.classList.toggle('hidden', isReturn);
        returnForm.classList.toggle('hidden', !isReturn);
        
        // Recalculer le total après changement de type
        updateTotalAmount();
    }

    if (transactionType) {
        transactionType.addEventListener('change', updateTransactionType);
        updateTransactionType();
    }

    // Initialisation des prix des produits
    function initProductPrices() {
        document.querySelectorAll('.product-select').forEach(select => {
            const selectedOption = select.selectedOptions[0];
            if (selectedOption) {
                const price = selectedOption.dataset.price || 0;
                const row = select.closest('tr');
                if (row) {
                    const priceInput = row.querySelector('.price-input');
                    if (priceInput) priceInput.value = price;
                    updateRowTotal(row);
                }
            }
        });
    }

    // Mise à jour du total par ligne
    function updateRowTotal(row) {
        if (!row) return;
        
        const quantityInput = row.querySelector('.quantity-input');
        const priceInput = row.querySelector('.price-input');
        const totalInput = row.querySelector('.total-input');
        
        if (!quantityInput || !priceInput || !totalInput) return;
        
        const quantity = parseFloat(quantityInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;
        totalInput.value = (quantity * price).toFixed(2);
        
        updateTotalAmount();
    }

    // Calcul du montant total global
    function updateTotalAmount() {
        const totalAmountInput = document.getElementById('total_amount');
        if (!totalAmountInput) return;
        
        let total = 0;
        document.querySelectorAll('.total-input').forEach(input => {
            total += parseFloat(input.value) || 0;
        });
        
        // Inverser le total pour les retours
        if (transactionType && transactionType.value === 'entry') {
            total = -Math.abs(total);
        }
        
        totalAmountInput.value = total.toFixed(2);
    }

    // Gestion des événements sur la table des ventes
    const salesTable = document.querySelector('#salesTable');
    if (salesTable) {
        // Changement de produit
        salesTable.addEventListener('change', function(e) {
            if (e.target.classList.contains('product-select')) {
                const selectedOption = e.target.selectedOptions[0];
                const row = e.target.closest('tr');
                
                if (selectedOption && row) {
                    const priceInput = row.querySelector('.price-input');
                    const quantityInput = row.querySelector('.quantity-input');
                    
                    if (priceInput) priceInput.value = selectedOption.dataset.price || 0;
                    
                    // Mise à jour du stock max pour les ventes
                    if (quantityInput && transactionType && transactionType.value !== 'entry') {
                        quantityInput.setAttribute('max', selectedOption.dataset.stock || 0);
                    }
                    
                    updateRowTotal(row);
                }
            }
        });

        // Changement de quantité
        salesTable.addEventListener('input', function(e) {
            if (e.target.classList.contains('quantity-input')) {
                const row = e.target.closest('tr');
                const quantityInput = e.target;
                const maxStock = parseInt(quantityInput.getAttribute('max')) || 0;
                const quantity = parseInt(quantityInput.value) || 0;
                
                // Validation du stock pour les ventes
                if (transactionType && transactionType.value !== 'entry' && quantity > maxStock) {
                    alert(`Quantité demandée (${quantity}) dépasse le stock disponible (${maxStock}).`);
                    quantityInput.value = maxStock;
                }
                
                updateRowTotal(row);
            }
        });

        // Suppression de ligne
        salesTable.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-row')) {
                const tbody = this.querySelector('tbody');
                if (tbody && tbody.rows.length > 1) {
                    e.target.closest('tr').remove();
                    updateTotalAmount();
                } else {
                    alert('Vous devez garder au moins un produit.');
                }
            }
        });
    }

    // Ajout d'une nouvelle ligne (uniquement en mode normal)
    const addRowBtn = document.getElementById('addRow');
    if (addRowBtn) {
        const fromCart = @json(session('from_cart', false));
        if (!fromCart) {
            addRowBtn.addEventListener('click', function() {
                const tbody = document.querySelector('#salesTable tbody');
                if (!tbody) return;
                
                const newRow = tbody.rows[0].cloneNode(true);
                
                // Réinitialisation des valeurs
                newRow.querySelectorAll('input').forEach(input => {
                    if (!input.readOnly) input.value = '';
                    if (input.classList.contains('total-input')) input.value = '0.00';
                });
                
                // Réinitialisation de la sélection
                const select = newRow.querySelector('.product-select');
                if (select) select.selectedIndex = 0;
                
                tbody.appendChild(newRow);
                initProductPrices();
            });
        }
    }

    // Gestion des retours
    const saleSelect = document.getElementById('sale_id');
    if (saleSelect) {
        saleSelect.addEventListener('change', function() {
            const selectedOption = this.selectedOptions[0];
            const tbody = document.getElementById('returnProductsBody');
            
            if (!selectedOption || !tbody) return;
            
            try {
                const products = JSON.parse(selectedOption.dataset.products || '[]');
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
                
                // Ajout des écouteurs d'événements pour les nouvelles lignes
                tbody.addEventListener('input', function(e) {
                    if (e.target.classList.contains('return-quantity')) {
                        calculateReturnTotal();
                    }
                });
            } catch (e) {
                console.error('Erreur lors du parsing des produits:', e);
            }
        });
    }

    // Calcul du total pour les retours
    function calculateReturnTotal() {
        const totalAmountInput = document.getElementById('total_amount');
        if (!totalAmountInput) return;
        
        let total = 0;
        document.querySelectorAll('#returnProductsBody tr').forEach(row => {
            const quantityInput = row.querySelector('.return-quantity');
            const priceInput = row.querySelector('input[name="price[]"]');
            
            if (quantityInput && priceInput) {
                total += (parseFloat(quantityInput.value) || 0) * (parseFloat(priceInput.value) || 0);
            }
        });
        
        totalAmountInput.value = total.toFixed(2);
    }

    // Gestion des modes de paiement
    if (paymentMode && creditCardFields) {
        paymentMode.addEventListener('change', function() {
            creditCardFields.style.display = this.value === 'credit_card' ? 'block' : 'none';
        });
    }

    // Initialisation
    initProductPrices();
});
</script>


@endsection