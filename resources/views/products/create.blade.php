@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <div class="bg-white shadow-md rounded-lg">
        <!-- Titre de la page -->
        <div class="px-6 py-4 border-b border-gray-200 bg-blue-100 rounded-t-lg">
            <h1 class="text-2xl font-semibold text-blue-700 flex items-center">
                <i class="fas fa-box mr-2"></i> Créer un produit pour {{ $supplier->name }}
            </h1>
        </div>

        <!-- Formulaire -->
        <div class="px-6 py-4">
            <form action="{{ route('products.store') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Champ caché pour l'ID du fournisseur -->
                <input type="hidden" name="supplier_id" value="{{ $supplier->id }}">

                <!-- Champ pour le nom du produit -->
                <div>
                    <label for="name" class="block text-gray-700 font-medium">Nom du produit</label>
                    <input type="text" name="name" id="name" class="form-input mt-1 block w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300" required>
                </div>

                <!-- Champ pour la description du produit -->
                <div>
                    <label for="description" class="block text-gray-700 font-medium">Description</label>
                    <textarea name="description" id="description" class="form-textarea mt-1 block w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300"></textarea>
                </div>

                <!-- Champ pour le prix du produit -->
                <div>
                    <label for="price" class="block text-gray-700 font-medium">Prix (€)</label>
                    <input type="number" name="price" id="price" class="form-input mt-1 block w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300" step="0.01" required>
                </div>

                <!-- Bouton de soumission -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                        <i class="fas fa-save mr-2"></i> Créer le produit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
