@extends('layouts.app')

@section('title', 'Gestion des fournisseurs')

@section('content')
<div class="container mx-auto py-6">
    <div class="bg-white shadow-md rounded-lg">
        <!-- Titre de la page -->
        <div class="px-6 py-4 border-b border-gray-200 bg-blue-100 rounded-t-lg">
            <h1 class="text-2xl font-semibold text-blue-700 flex items-center">
                <i class="fas fa-truck mr-2"></i> Gestion des fournisseurs
            </h1>
        </div>

        <!-- Bouton pour ajouter un fournisseur -->
        <div class="px-6 py-4">
            <a href="{{ route('suppliers.create') }}" class="btn btn-primary flex items-center gap-2">
                <i class="fas fa-plus"></i> Ajouter un fournisseur
            </a>
        </div>

        <!-- Tableau des fournisseurs -->
        <div class="px-6 py-4">
            <div class="overflow-x-auto">
                <table class="table-auto w-full bg-gray-50 border-collapse border border-gray-200 text-sm shadow-lg rounded-lg">
                    <thead class="bg-blue-50">
                        <tr>
                            <th class="border px-4 py-2 text-left text-gray-700 font-medium">Nom</th>
                            <th class="border px-4 py-2 text-left text-gray-700 font-medium">Email</th>
                            <th class="border px-4 py-2 text-left text-gray-700 font-medium">Téléphone</th>
                            <th class="border px-4 py-2 text-left text-gray-700 font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($suppliers as $supplier)
                        <tr class="hover:bg-gray-100 transition duration-200">
                            <td class="border px-4 py-2 text-gray-800 font-medium">{{ $supplier->name }}</td>
                            <td class="border px-4 py-2 text-gray-800">{{ $supplier->email }}</td>
                            <td class="border px-4 py-2 text-gray-800">{{ $supplier->telephone }}</td>
                            <td class="border px-4 py-2">
                                <!-- Actions -->
                                <div class="inline-flex gap-2">
                                    <a href="{{ route('suppliers.edit', $supplier->id) }}" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-edit"></i> Modifier
                                    </a>
                                    <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur ?')">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </form>
                                    <a href="{{ route('products.create', $supplier->id) }}" class="text-green-600 hover:text-green-800">
                                        <i class="fas fa-box"></i> Ajouter produit
                                    </a>
                                    <a href="{{ route('orders.create', $supplier->id) }}" class="text-yellow-600 hover:text-yellow-800">
                                        <i class="fas fa-shopping-cart"></i> Commander
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4">
            <div class="d-flex justify-content-center">
                {{ $suppliers->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
