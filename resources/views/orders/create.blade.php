@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg">
        <!-- Titre de la page -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-blue-100 dark:bg-blue-900 rounded-t-lg">
            <h1 class="text-2xl font-semibold text-blue-700 dark:text-blue-400 flex items-center">
                <i class="fas fa-shopping-cart mr-2"></i> Créer une commande
            </h1>
        </div>

        <!-- Formulaire -->
        <div class="px-6 py-4">
            <form action="{{ route('orders.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Champ pour utilisateur -->
                <div>
                    <label for="user_id" class="block text-gray-700 dark:text-gray-200 font-medium">Utilisateur</label>
                    <select name="user_id" id="user_id" class="form-select mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500" required disabled>
                        <option value="{{ auth()->id() }}">{{ auth()->user()->name }}</option>
                    </select>
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                </div>

                <!-- Champ pour fournisseur -->
                <div>
                    <label for="supplier_id" class="block text-gray-700 dark:text-gray-200 font-medium">Fournisseur</label>
                    <select name="supplier_id" id="supplier_id" class="form-select mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500" required disabled>
                        <option value="{{ $supplier_id }}">{{ \App\Models\Supplier::find($supplier_id)->name }}</option>
                    </select>
                    <input type="hidden" name="supplier_id" value="{{ $supplier_id }}">
                </div>

                <!-- Champ pour la date -->
                <div>
                    <label for="date" class="block text-gray-700 dark:text-gray-200 font-medium">Date</label>
                    <input type="date" name="date" id="date" class="form-input mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500" required>
                </div>

                <!-- Section des éléments de commande -->
                <div>
                    <label for="items" class="block text-gray-700 dark:text-gray-200 font-medium">Éléments de commande</label>
                    <div id="items" class="space-y-4">
                        <div class="flex items-center space-x-4 item">
                            <select name="items[0][product_id]" class="form-select mt-1 flex-1 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500" required>
                                @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                            <input type="number" name="items[0][quantity]" class="form-input mt-1 flex-1 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500" placeholder="Quantité" required>
                        </div>
                    </div>
                    <button type="button" id="add-item" class="mt-4 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium py-2 px-4 rounded-lg">
                        <i class="fas fa-plus"></i> Ajouter un élément
                    </button>
                </div>

                <!-- Bouton de soumission -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                        <i class="fas fa-save mr-2"></i> Créer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('add-item').addEventListener('click', function() {
        const itemContainer = document.getElementById('items');
        const itemCount = itemContainer.getElementsByClassName('item').length;
        const newItem = document.createElement('div');
        newItem.classList.add('flex', 'items-center', 'space-x-4', 'item');
        newItem.innerHTML = `
            <select name="items[${itemCount}][product_id]" class="form-select mt-1 flex-1 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500" required>
                @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
            <input type="number" name="items[${itemCount}][quantity]" class="form-input mt-1 flex-1 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-300 dark:focus:ring-blue-500" placeholder="Quantité" required>
        `;
        itemContainer.appendChild(newItem);
    });
</script>
@endsection