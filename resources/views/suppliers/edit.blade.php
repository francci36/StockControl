@extends('layouts.app')

@section('title', 'Modifier le fournisseur')

@section('content')
<div class="container mx-auto py-6">
    <div class="bg-white shadow-md rounded-lg">
        <!-- Titre de la page -->
        <div class="px-6 py-4 border-b border-gray-200 bg-blue-100 rounded-t-lg">
            <h1 class="text-2xl font-semibold text-blue-700 flex items-center">
                <i class="fas fa-edit mr-2"></i> Modifier le fournisseur
            </h1>
        </div>

        <!-- Formulaire -->
        <div class="px-6 py-4">
            <form method="POST" action="{{ route('suppliers.update', $supplier->id) }}" class="space-y-4">
                @csrf
                @method('PATCH')
                <div>
                    <label for="name" class="block text-gray-700 font-medium">Nom</label>
                    <input type="text" name="name" id="name" class="form-input mt-1 block w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300" value="{{ $supplier->name }}" required>
                </div>
                <div>
                    <label for="email" class="block text-gray-700 font-medium">Email</label>
                    <input type="email" name="email" id="email" class="form-input mt-1 block w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300" value="{{ $supplier->email }}" required>
                </div>
                <div>
                    <label for="telephone" class="block text-gray-700 font-medium">Téléphone</label>
                    <input type="text" name="telephone" id="telephone" class="form-input mt-1 block w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300" value="{{ $supplier->telephone }}" required>
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
