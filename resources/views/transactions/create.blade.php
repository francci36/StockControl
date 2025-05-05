@extends('layouts.app')

@section('title', 'Nouvelle Transaction')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
    <!-- En-tête -->
    <h2 class="text-2xl font-semibold mb-6 text-blue-600 dark:text-blue-400 flex items-center">
        <i class="fas fa-shopping-cart mr-2"></i> Nouvelle Transaction
    </h2>

    <!-- Selection du type de transaction -->
    <div class="mb-4">
        <label for="transaction_type" class="block text-gray-800 dark:text-gray-600 font-medium">Type de transaction :</label>
        <select name="transaction_type" id="transaction_type" class="form-select mt-1 block w-full focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500 rounded-lg bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">
            <option value="sale">Vente</option>
            <option value="entry">Retour</option>
        </select>
    </div>

    <!-- Formulaire -->
    <form action="{{ request('transaction_type') === 'entry' ? route('transactions.storeReturn') : route('transactions.store') }}" method="POST" class="space-y-4" id="transactionForm">       
         @csrf
         <input type="hidden" name="debug_data" value="{{ json_encode(request()->all()) }}">
        <input type="hidden" name="type" id="transaction_type_field" value="exit">
        
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
        
        <!-- Champs raison du retour -->
        <div id="returnReason" class="hidden mt-4">
            <label for="reason" class="block text-gray-800 dark:text-gray-600 font-medium">Raison du retour :</label>
            <textarea name="reason" id="reason" rows="3" class="form-textarea mt-1 w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg"></textarea>
        </div>

        <!-- Champ "Référence de la vente originale" pour les retours -->
        <div id="originalSaleField" class="mt-4 hidden">
            <label for="original_sale_id" class="block text-gray-800 dark:text-gray-600 font-medium">Référence de la vente originale :</label>
            <select name="original_sale_id" id="original_sale_id" class="form-select mt-1 w-full bg-white dark:bg-gray-700 border text-gray-800 dark:text-gray-200 rounded-lg">
                <option value="">Sélectionner la vente originale</option>
                @foreach($sales as $sale)
                <option value="{{ $sale->id }}">Vente #{{ $sale->id }} - {{ $sale->created_at->format('d/m/Y H:i') }} - {{ number_format($sale->total_amount, 2, ',', ' ') }} €</option>
                @endforeach
            </select>
        </div>

        <!-- Bouton pour ajouter une ligne -->
        <button type="button" id="addRow" class="mt-4 bg-green-500 hover:bg-green-700 text-white p-2 rounded">
            Ajouter un produit
        </button>

        <!-- Section des modes de paiement -->
        <div id="paymentSection" class="mt-6">
            <label for="payment_mode" class="block text-gray-800 dark:text-gray-600 font-medium">Mode de paiement :</label>
            <select name="payment_mode" id="payment_mode" class="form-select mt-1 block w-full focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500 rounded-lg bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200">
                <option value="cash">Espèces</option>
                <option value="credit_card">Carte de crédit</option>
                <option value="paypal">PayPal</option>
                <option value="stripe">Stripe</option>
                <option value="bank_transfer">Virement bancaire</option>
                <option value="refund" class="hidden">Remboursement</option>
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
            <input type="tel" name="card_number" id="card_number" class="form-input mt-1 w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg" maxlength="19" placeholder="XXXX XXXX XXXX XXXX" pattern="[0-9 ]+" inputmode="numeric" title="Veuillez entrer uniquement des chiffres">

            <label for="card_expiry" class="block text-gray-800 dark:text-gray-600 font-medium">Date d'expiration (MM/AA) :</label>
            <input type="text" name="card_expiry" id="card_expiry" class="form-input mt-1 w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg" placeholder="MM/AA" maxlength="5" pattern="^(0[1-9]|1[0-2])\/[0-9]{2}$" inputmode="numeric" title="Format attendu : MM/AA">

            <label for="card_cvv" class="block text-gray-800 dark:text-gray-600 font-medium mt-2">Code CVV :</label>
            <input type="tel" name="card_cvv" id="card_cvv" class="form-input mt-1 w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg" maxlength="3" placeholder="XXX" pattern="[0-9]{3}" inputmode="numeric" title="Veuillez entrer un code CVV à 3 chiffres">
        </div>

        <!-- Champs PayPal -->
        <div id="paypalFields" style="display: none;" class="mt-6">
            <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white p-2 rounded-lg shadow">
                Connexion à PayPal
            </button>
        </div>

        <!-- Section Stripe -->
        <div id="stripeFields" style="display: none;" class="mt-6">
            <div id="stripe-card-element" class="p-3 border rounded-lg">
                <!-- Stripe injectera les champs ici -->
            </div>
            <div id="stripe-card-errors" role="alert" class="text-red-500 mt-2"></div>
        </div>

        <!-- Montant total -->
        <div class="mt-6">
            <label for="total_amount" class="block text-gray-800 dark:text-gray-600 font-medium">Montant total :</label>
            <input type="number" name="total_amount" id="total_amount" class="form-input mt-1 w-full bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-800 dark:text-gray-200 rounded-lg" readonly>
        </div>

        <!-- Bouton de soumission -->
        <div class="flex items-center justify-end mt-4">
            <button type="submit" id="submitButton" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md">
                Finaliser la Vente
            </button>
        </div>
    </form>
</div>

<!-- JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Gestion du type de transaction
        const transactionType = document.getElementById('transaction_type');
        const transactionTypeField = document.getElementById('transaction_type_field');
        const returnReason = document.getElementById('returnReason');
        const originalSaleField = document.getElementById('originalSaleField');
        const paymentSection = document.getElementById('paymentSection');
        const submitButton = document.getElementById('submitButton');
        const paymentMode = document.getElementById('payment_mode');
        const refundOption = paymentMode.querySelector('option[value="refund"]');
        
        function updateTransactionType() {
            const isEntry = transactionType.value === 'entry';
            
            // Mise à jour du champ caché type
            transactionTypeField.value = isEntry ? 'entry' : 'exit';
            
            // Mise à jour des champs visibles
            returnReason.classList.toggle('hidden', !isEntry);
            originalSaleField.classList.toggle('hidden', !isEntry);
            paymentSection.classList.toggle('hidden', isEntry);
            
            // Mise à jour du texte du bouton
            submitButton.textContent = isEntry ? 'Enregistrer le Retour' : 'Finaliser la Vente';
            
            // Si c'est un retour, forcer le mode de paiement à "remboursement"
            if (isEntry) {
                refundOption.classList.remove('hidden');
                paymentMode.value = 'refund';
            } else {
                refundOption.classList.add('hidden');
                if (paymentMode.value === 'refund') {
                    paymentMode.value = 'cash';
                }
            }
        }
        
        transactionType.addEventListener('change', updateTransactionType);
        updateTransactionType(); // Initialisation
        
        // Gestion de la sélection de la vente originale
        document.getElementById('original_sale_id')?.addEventListener('change', function() {
            const saleId = this.value;
            if (!saleId) return;
            
            // Ici, vous pourriez ajouter une requête AJAX pour récupérer les produits de la vente originale
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

    });
</script>
@endsection