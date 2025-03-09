@extends('layouts.app')

@section('title', 'Détails de la commande')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6">Détails de la commande #{{ $order->id }}</h1>

    <div class="bg-white rounded-lg shadow p-6">
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <p class="text-gray-600">Fournisseur</p>
                <p class="font-semibold">{{ $order->supplier->name }}</p>
            </div>
            <div>
                <p class="text-gray-600">Date</p>
                <p class="font-semibold">{{ $order->date->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-gray-600">Statut</p>
                <p class="font-semibold">{{ $order->status }}</p>
            </div>
        </div>

        <!-- Formulaire de mise à jour du statut -->
        <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST">
            @csrf
            @method('PUT')
            <select name="status" class="form-control mb-4">
                <option value="en attente" {{ $order->status === 'en attente' ? 'selected' : '' }}>En attente</option>
                <option value="arrivé" {{ $order->status === 'arrivé' ? 'selected' : '' }}>Arrivé</option>
            </select>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </form>

        <h2 class="text-xl font-semibold mb-4 mt-6">Produits commandés</h2>
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produit</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantité</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($order->products as $product)
                    <tr>
                        <td class="px-6 py-4">{{ $product->name }}</td>
                        <td class="px-6 py-4">{{ $product->pivot->quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
