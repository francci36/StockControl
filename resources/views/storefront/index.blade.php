@extends('layouts.app')

@section('title', 'Boutique')

@section('content')
<div class="container mx-auto py-6">
    <h2 class="text-3xl font-semibold text-center text-blue-600 dark:text-blue-400">Nos produits</h2>

    <!-- Barre de recherche -->
    <div class="my-6 text-center">
        <input type="text" id="search-product" placeholder="🔍 Rechercher un produit..."
               class="px-4 py-2 border rounded-md bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
    </div>

    <!-- Affichage des produits -->
    <div id="product-list" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($products as $product)
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-4 text-center">
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-40 object-cover rounded-lg mb-4">
            <h3 class="text-lg font-bold text-gray-700 dark:text-gray-200">{{ $product->name }}</h3>
            <p class="text-gray-500 dark:text-gray-400">{{ $product->description }}</p>
            <p class="text-lg font-semibold text-blue-600 dark:text-blue-400 mt-2">{{ number_format($product->price, 2) }} €</p>
            <a href="#" class="mt-4 bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Acheter</a>
        </div>
        @endforeach
    </div>
</div>
@endsection
