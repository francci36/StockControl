@extends('layouts.app')

@section('title', 'Gestion des fournisseurs')

@section('content')
<div class="container">
    <h1 class="my-4">Gestion des fournisseurs</h1>

    <!-- Bouton pour ajouter un fournisseur -->
    <div class="mb-4">
        <a href="{{ route('suppliers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Ajouter un fournisseur
        </a>
    </div>

    <!-- Tableau des fournisseurs -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Nom</th>
                            <th scope="col">Email</th>
                            <th scope="col">Téléphone</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($suppliers as $supplier)
                        <tr>
                            <td>{{ $supplier->name }}</td>
                            <td>{{ $supplier->email }}</td>
                            <td>{{ $supplier->telephone }}</td>
                            <td>
                                <!-- Menu déroulant pour les actions -->
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton{{ $supplier->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        Actions
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $supplier->id }}">
                                        <a href="{{ route('suppliers.edit', $supplier->id) }}" class="dropdown-item">
                                            <i class="fas fa-edit"></i> Modifier
                                        </a>
                                        <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur ?')">
                                                <i class="fas fa-trash"></i> Supprimer
                                            </button>
                                        </form>
                                        <a href="{{ route('products.create', $supplier->id) }}" class="dropdown-item">
                                            <i class="fas fa-box"></i> Ajouter un produit
                                        </a>
                                        <a href="{{ route('orders.create', $supplier->id) }}" class="dropdown-item">
                                            <i class="fas fa-shopping-cart"></i> Passer une commande
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $suppliers->links() }}
    </div>
</div>
@endsection