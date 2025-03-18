@extends('layouts.app')

@section('title', 'Ajouter un fournisseur')

@section('content')
<div class="container mx-auto py-6">
    <div class="bg-white shadow-md rounded-lg">
        <!-- Titre de la page -->
        <div class="px-6 py-4 border-b border-gray-200 bg-blue-100 rounded-t-lg">
            <h1 class="text-2xl font-semibold text-blue-700 flex items-center">
                <i class="fas fa-user-plus mr-2"></i> Ajouter un fournisseur
            </h1>
        </div>

        <!-- Formulaire -->
        <div class="px-6 py-4">
            <form method="POST" action="{{ route('suppliers.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="name" class="block text-gray-700 font-medium">Nom</label>
                    <input type="text" name="name" id="name" class="form-input mt-1 block w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300" required>
                </div>
                <div>
                    <label for="email" class="block text-gray-700 font-medium">Email</label>
                    <input type="email" name="email" id="email" class="form-input mt-1 block w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300" required>
                </div>
                <div>
                    <label for="telephone" class="block text-gray-700 font-medium">Téléphone</label>
                    <input type="text" name="telephone" id="telephone" class="form-input mt-1 block w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300" required>
                </div>
                <div>
                    <label for="products" class="block text-gray-700 font-medium">Produits</label>
                    <select name="products[]" id="products" class="form-select mt-1 block w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300" multiple>
                        @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg">
                        <i class="fas fa-save mr-2"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
