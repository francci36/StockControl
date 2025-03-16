@extends('layouts.app')

@section('title', 'Liste des commandes')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6">Liste des commandes</h1>

    <div class="bg-white rounded-lg shadow p-6">
        <table class="min-w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fournisseur</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach ($orders as $order)
                    <tr>
                        <td class="px-6 py-4">{{ $order->id }}, Quantity: {{ $order->pivot ? $order->pivot->quantity : 'N/A' }}</td>
                        <td class="px-6 py-4">{{ $order->supplier->name }}</td>
                        <td class="px-6 py-4">{{ $order->date->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">{{ $order->status }}</td>
                        <td class="px-6 py-4">
                            <!-- Formulaire de mise à jour du statut -->
                            <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="form-control mb-4">
                                    <option value="en attente" {{ $order->status === 'en attente' ? 'selected' : '' }}>En attente</option>
                                    <option value="arrivé" {{ $order->status === 'arrivé' ? 'selected' : '' }}>Arrivé</option>
                                </select>
                                <button type="submit" class="btn btn-primary">Mettre à jour</button>
                            </form>
                            <!-- Formulaire de suppression -->
                            <form action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette commande ?')">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
