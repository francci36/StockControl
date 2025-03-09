@extends('layouts.app')

@section('title', 'Gestion des fournisseurs')

@section('content')
<div class="container">
    <h1>Fournisseurs</h1>
    <a href="{{ route('suppliers.create') }}" class="btn btn-primary">Ajouter un fournisseur</a>
    <table class="table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suppliers as $supplier)
            <tr>
                <td>{{ $supplier->name }}</td>
                <td>{{ $supplier->email }}</td>
                <td>{{ $supplier->telephone }}</td>
                <td>
                    <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-warning">Modifier</a>
                    <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                    <a href="{{ route('products.create', $supplier->id) }}" class="btn btn-secondary">Ajouter un produit</a>
                    <a href="{{ route('orders.create', $supplier->id) }}" class="btn btn-success">Passer une commande</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
