@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg">
        <!-- Titre de la page -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-blue-100 dark:bg-blue-900 rounded-t-lg">
            <h1 class="text-2xl font-semibold text-blue-700 dark:text-blue-400 flex items-center">
                <i class="fas fa-box mr-2"></i> Ajouter un produit
            </h1>
        </div>

        <!-- Formulaire -->
        <div class="px-6 py-4">
            <form method="POST" action="{{ route('suppliers.storeWithProducts') }}" class="space-y-4">
                @csrf

                <!-- Champ Nom -->
                <div>
                    <label for="name" class="block text-gray-700 dark:text-gray-600 font-medium">Nom du fournisseur</label>
                    <input type="text" id="name" value="{{ $supplier->name }}" 
                           class="form-input mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-700 dark:text-gray-200" readonly>
                    <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">
                </div>

                <!-- Champ Email -->
                <div>
                    <label for="email" class="block text-gray-700 dark:text-gray-600 font-medium">Email</label>
                    <input type="email" id="email" value="{{ $supplier->email }}" 
                           class="form-input mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-700 dark:text-gray-200" readonly>
                </div>

                <!-- Champ Téléphone -->
                <div>
                    <label for="telephone" class="block text-gray-700 dark:text-gray-600 font-medium">Téléphone</label>
                    <input type="text" id="telephone" value="{{ $supplier->telephone }}" 
                           class="form-input mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-700 dark:text-gray-200" readonly>
                </div>

                <!-- Produits -->
                <div id="products-section">
                    <label class="block text-gray-700 dark:text-gray-600 font-medium">Produits</label>

                    <!-- Produit par défaut -->
                    <div class="product-item flex flex-wrap items-center space-x-4">
                        <input type="text" name="products[0][name]" class="form-input mt-1 flex-1 w-full sm:w-auto border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-200" placeholder="Nom du produit" required>
                        <input type="number" name="products[0][price]" class="form-input mt-1 flex-1 w-full sm:w-auto border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-200" placeholder="Prix" step="0.01" required>
                        <input type="number" name="products[0][stock_threshold]" class="form-input mt-1 flex-1 w-full sm:w-auto border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-200" placeholder="Seuil de stock">
                        <input type="text" name="products[0][description]" class="form-input mt-1 flex-1 w-full sm:w-auto border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-200" placeholder="Description">
                        <button type="button" class="remove-product bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded-lg mt-2 sm:mt-0">
                            Supprimer
                        </button>
                    </div>
                </div>

                <!-- Bouton pour ajouter plus de produits -->
                <button type="button" id="add-product" class="mt-4 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium py-2 px-4 rounded-lg">
                    <i class="fas fa-plus"></i> Ajouter un produit
                </button>

                <!-- Bouton de soumission -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                        <i class="fas fa-save mr-2"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('add-product').addEventListener('click', function () {
        const productsSection = document.getElementById('products-section');
        const index = productsSection.getElementsByClassName('product-item').length;

        // Créer un nouvel élément produit
        const newProduct = document.createElement('div');
        newProduct.classList.add('product-item', 'flex', 'flex-wrap', 'items-center', 'space-x-4', 'mt-2');
        newProduct.innerHTML = `
            <input type="text" name="products[${index}][name]" class="form-input mt-1 flex-1 w-full sm:w-auto border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-200" placeholder="Nom du produit" required>
            <input type="number" name="products[${index}][price]" class="form-input mt-1 flex-1 w-full sm:w-auto border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-200" placeholder="Prix" step="0.01" required>
            <input type="number" name="products[${index}][stock_threshold]" class="form-input mt-1 flex-1 w-full sm:w-auto border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-200" placeholder="Seuil de stock">
            <input type="text" name="products[${index}][description]" class="form-input mt-1 flex-1 w-full sm:w-auto border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500 dark:bg-gray-700 dark:text-gray-200" placeholder="Description">
            <button type="button" class="remove-product bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded-lg mt-2 sm:mt-0">
                Supprimer
            </button>
        `;

        productsSection.appendChild(newProduct);

        // Ajouter un gestionnaire d'événement pour supprimer
        newProduct.querySelector('.remove-product').addEventListener('click', function () {
            newProduct.remove();
        });
    });

    // Ajout d'un gestionnaire de suppression pour les produits initiaux
    document.querySelectorAll('.remove-product').forEach(button => {
        button.addEventListener('click', function () {
            button.closest('.product-item').remove();
        });
    });
</script>
@endsection
