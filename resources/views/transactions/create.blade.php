@extends('layouts.app')

@section('title', 'Nouvelle Transaction')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
    <!-- En-tête -->
    <h2 class="text-2xl font-semibold mb-6 text-blue-600 dark:text-blue-400 flex items-center">
        <i class="fas fa-exchange-alt mr-2"></i> Nouvelle Transaction
    </h2>

    <!-- Selection du type de transaction -->
    <div class="mb-4">
        <label for="transaction_type" class="block text-gray-800 dark:text-gray-600 font-medium">Type de transaction :</label>
        <select name="transaction_type" id="transaction_type" class="form-select mt-1 block w-full focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500 rounded-lg bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">
            <option value="exit">Vente</option>
            <option value="entry">Retour</option>
        </select>
    </div>

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
                </tbody>
            </table>
        </div>

        <!-- Bouton pour ajouter une ligne -->
        <button type="button" id="addRow" class="mt-4 bg-green-500 hover:bg-green-700 text-white p-2 rounded">
            Ajouter un produit
        </button>

        <!-- Section des modes de paiement -->
        <div class="mt-6">
            <label for="payment_mode" class="block text-gray-800 dark:text-gray-600 font-medium">Mode de paiement :</label>
            <select name="payment_mode" id="payment_mode" class="form-select mt-1 block w-full focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500 rounded-lg bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">
                <option value="cash">Espèces</option>
                <option value="credit_card">Carte de crédit</option>
                <option value="paypal">PayPal</option>
                <option value="stripe">Stripe</option>
                <option value="bank_transfer">Virement bancaire</option>
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
            <input type="number" name="total_amount" id="total_amount" class="form-input mt-1 w-full bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg" readonly>
        </div>

        <!-- Bouton de soumission -->
        <div class="flex items-center justify-end mt-4">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md">
                Finaliser la Vente
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
    document.addEventListener('DOMContentLoaded', function () {
        // Gestion du type de transaction
        const transactionType = document.getElementById('transaction_type');
    const saleForm = document.getElementById('saleForm');
    const returnForm = document.getElementById('returnForm');
    
    function updateTransactionType() {
        const isReturn = transactionType.value === 'entry';
        saleForm.classList.toggle('hidden', isReturn);
        returnForm.classList.toggle('hidden', !isReturn);
    }
    
    transactionType.addEventListener('change', updateTransactionType);
    updateTransactionType();

        
          // Gestion des produits pour les retours
    document.getElementById('sale_id').addEventListener('change', function() {
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
                    <input type="number" name="quantity[]" min="1" max="${product.pivot.quantity}" 
                           class="w-full rounded-lg border-gray-300" required>
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
        
        // Ajout d'une nouvelle ligne au tableau
        document.getElementById('addRow').addEventListener('click', function () {
            const table = document.getElementById('salesTable').getElementsByTagName('tbody')[0];
            const newRow = table.rows[0].cloneNode(true);
            
            // Réinitialiser les valeurs
            newRow.querySelectorAll('input').forEach(input => input.value = '');
            
            // Réinitialiser la sélection du produit
            const select = newRow.querySelector('.product-select');
            select.selectedIndex = 0;
            const selectedOption = select.options[select.selectedIndex];
            const price = selectedOption.getAttribute('data-price');
            newRow.querySelector('.price-input').value = price;
            
            table.appendChild(newRow);
        });

        // Suppression d'une ligne
        document.getElementById('salesTable').addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-row')) {
                const tbody = this.getElementsByTagName('tbody')[0];
                if (tbody.rows.length > 1) {
                    e.target.closest('tr').remove();
                    calculateTotalAmount();
                } else {
                    alert('Vous ne pouvez pas supprimer la dernière ligne.');
                }
            }
        });

        // Validation de la quantité et mise à jour du total
        document.querySelector('#salesTable').addEventListener('input', function (e) {
            if (e.target.classList.contains('quantity-input')) {
                const row = e.target.closest('tr');
                const quantityInput = row.querySelector('.quantity-input');
                const productSelect = row.querySelector('.product-select');
                const selectedOption = productSelect.options[productSelect.selectedIndex];
                const availableStock = parseInt(selectedOption.getAttribute('data-stock'));
                const quantity = parseInt(quantityInput.value);
                const isEntry = document.getElementById('transaction_type').value === 'entry';

                // Validation de la quantité
                if (!isEntry && quantity > availableStock) {
                    alert(`Quantité demandée (${quantity}) dépasse le stock disponible (${availableStock}).`);
                    quantityInput.value = availableStock;
                }

                // Calcul du total pour chaque ligne
                const price = parseFloat(row.querySelector('.price-input').value);
                const total = quantity * price;
                row.querySelector('.total-input').value = isNaN(total) ? '' : total.toFixed(2);
                
                // Calcul du montant total
                calculateTotalAmount();
            }
        });

        // Mise à jour des informations du produit
        document.querySelector('#salesTable').addEventListener('change', function (e) {
            if (e.target.classList.contains('product-select')) {
                const selectedOption = e.target.options[e.target.selectedIndex];
                const price = selectedOption.getAttribute('data-price');
                const stock = selectedOption.getAttribute('data-stock');
                const row = e.target.closest('tr');
                
                row.querySelector('.price-input').value = price;
                
                // Mise à jour de la quantité maximale uniquement pour les ventes (pas pour les retours)
                if (document.getElementById('transaction_type').value !== 'entry') {
                    row.querySelector('.quantity-input').setAttribute('max', stock);
                }
                
                // Déclencher le calcul
                row.querySelector('.quantity-input').dispatchEvent(new Event('input'));
            }
        });

        document.querySelectorAll('.quantity-input').forEach(input => {
    input.addEventListener('input', function () {
        const maxStock = parseInt(this.getAttribute('max')) || 0;

        if (document.getElementById('transaction_type').value === 'entry') {
            this.removeAttribute('max'); // Supprime la limite de stock
        } else {
            if (parseInt(this.value) > maxStock) {
                this.value = maxStock;
                alert("Quantité indisponible !");
            }
        }
    });
});


        // Fonction pour calculer le montant total
        function calculateTotalAmount() {
            let totalAmount = 0;
            document.querySelectorAll('.total-input').forEach(input => {
                totalAmount += parseFloat(input.value) || 0;
            });
            
            // Si c'est un retour, le montant doit être négatif
            if (document.getElementById('transaction_type').value === 'entry') {
                totalAmount = -Math.abs(totalAmount);
            }
            
            document.getElementById('total_amount').value = totalAmount.toFixed(2);
        }

        // Initialisation de la première ligne
        const firstRow = document.querySelector('#salesTable tbody tr');
        if (firstRow) {
            const firstOption = firstRow.querySelector('.product-select').options[0];
            const price = firstOption.getAttribute('data-price');
            const stock = firstOption.getAttribute('data-stock');
            firstRow.querySelector('.price-input').value = price;
            
            // Mise à jour de la quantité maximale uniquement pour les ventes (pas pour les retours)
            if (document.getElementById('transaction_type').value !== 'entry') {
                firstRow.querySelector('.quantity-input').setAttribute('max', stock);
            }
        }

        // Gestion de l'affichage des options de paiement et des logos
        document.getElementById('payment_mode').addEventListener('change', function () {
            const mode = this.value;
            const isEntry = document.getElementById('transaction_type').value === 'entry';

            // Désactiver la modification du mode de paiement pour les retours
            if (isEntry) {
                this.value = 'refund';
                return;
            }

            // Gestion de l'affichage des champs dynamiques
            const creditCardFields = document.getElementById('creditCardFields');
            const creditCardLogos = document.getElementById('creditCardLogos');
            creditCardFields.style.display = mode === 'credit_card' ? 'block' : 'none';
            creditCardLogos.style.display = mode === 'credit_card' ? 'flex' : 'none';

            // Activation des champs de la carte de crédit
            const inputs = creditCardFields.querySelectorAll('input');
            if (mode === 'credit_card') {
                inputs.forEach(input => {
                    input.disabled = false; // Activer les champs

                    input.addEventListener('input', function () {
                        if (input.id === 'card_number') {
                            // Autorise uniquement les chiffres et espaces pour le numéro de carte
                            let value = input.value.replace(/\s/g, ''); // Supprime tous les espaces
                            value = value.replace(/[^0-9]/g, ''); // Supprime les caractères non numériques
                            input.value = value.replace(/(\d{4})(?=\d)/g, '$1 '); // Ajoute des espaces après chaque bloc de 4 chiffres
                        } else if (input.id === 'card_expiry') {
                            // Validation dynamique pour le format MM/AA
                            input.value = input.value.replace(/[^0-9/]/g, ''); // Supprime les caractères non valides
                            if (input.value.length === 2 && !input.value.includes('/')) {
                                input.value += '/'; // Ajoute "/" après les deux premiers chiffres
                            }
                            if (input.value.length > 5) {
                                input.value = input.value.slice(0, 5); // Limite à 5 caractères
                            }
                        } else if (input.id === 'card_cvv') {
                            // Autorise uniquement les chiffres pour le CVV
                            input.value = input.value.replace(/[^0-9]/g, ''); // Supprime les caractères non numériques
                        }
                    });
                });
            } else {
                inputs.forEach(input => input.disabled = true); // Désactiver les champs
            }

            // Affichage des champs PayPal et Stripe
            document.getElementById('paypalFields').style.display = mode === 'paypal' ? 'block' : 'none';
            document.getElementById('stripeFields').style.display = mode === 'stripe' ? 'block' : 'none';
        });

        // Initialisation de Stripe
        function initStripe() {
            const stripe = Stripe('{{ env("STRIPE_KEY") }}');
            const elements = stripe.elements();
            const cardElement = elements.create('card');
            cardElement.mount('#stripe-card-element');

            // Gestion des erreurs
            cardElement.addEventListener('change', function(event) {
                const displayError = document.getElementById('stripe-card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });

            // Intercepter la soumission du formulaire
            document.getElementById('transactionForm').addEventListener('submit', function(e) {
                if (document.getElementById('payment_mode').value === 'stripe') {
                    e.preventDefault();
                    
                    stripe.createPaymentMethod({
                        type: 'card',
                        card: cardElement,
                    }).then(function(result) {
                        if (result.error) {
                            document.getElementById('stripe-card-errors').textContent = result.error.message;
                        } else {
                            // Ajouter le payment_method au formulaire
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'stripe_payment_method';
                            input.value = result.paymentMethod.id;
                            document.getElementById('transactionForm').appendChild(input);
                            
                            // Soumettre le formulaire
                            document.getElementById('transactionForm').submit();
                        }
                    });
                }
            });
        }

        // Chargement de Stripe si nécessaire
        document.getElementById('payment_mode').addEventListener('change', function() {
            if (this.value === 'stripe' && typeof Stripe === 'undefined') {
                const script = document.createElement('script');
                script.src = 'https://js.stripe.com/v3/';
                script.onload = function() {
                    initStripe();
                };
                document.head.appendChild(script);
            }
        });

        document.getElementById('transactionForm').addEventListener('submit', function() {
    document.getElementById('debug_data').value = JSON.stringify(Object.fromEntries(new FormData(this)));
    });

    // Validation spécifique pour les retours
    document.getElementById('returnForm').addEventListener('submit', function(e) {
        const quantities = document.querySelectorAll('#returnForm input[name="quantity[]"]');
        let isValid = true;
        
        quantities.forEach(input => {
            const max = parseInt(input.getAttribute('max'));
            const value = parseInt(input.value) || 0;
            
            if (value > max) {
                alert(`La quantité retournée ne peut pas dépasser ${max}`);
                isValid = false;
            }
        });

        if (!isValid) {
            e.preventDefault();
        }
    });
});

    
</script>
@endsection