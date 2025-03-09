<!-- resources/views/transactions/create.blade.php -->
@extends('layouts.app')

@section('title', 'Nouvelle transaction')

@section('content')
<div class="bg-white p-6 rounded-lg shadow">
    <h2 class="text-2xl font-semibold mb-6">Nouvelle transaction</h2>
    <form action="{{ route('transactions.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="product_id" class="block text-gray-700">Produit :</label>
            <select name="product_id" id="product_id" class="form-select mt-1 block w-full">
                @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="quantity" class="block text-gray-700">Quantité :</label>
            <input type="number" name="quantity" id="quantity" class="form-input mt-1 block w-full" required>
        </div>
        <div class="mb-4">
            <label for="type" class="block text-gray-700">Type :</label>
            <select name="type" id="type" class="form-select mt-1 block w-full">
                <option value="entry">Entrée</option>
                <option value="exit">Sortie</option>
            </select>
        </div>
        <div class="flex items-center justify-end mt-4">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Enregistrer</button>
        </div>
    </form>
</div>
@endsection
