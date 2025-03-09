<!-- resources/views/orders/create.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Créer une commande</h1>
    <form action="{{ route('orders.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="user_id">Utilisateur</label>
            <select name="user_id" id="user_id" class="form-control">
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="supplier_id">Fournisseur</label>
            <select name="supplier_id" id="supplier_id" class="form-control">
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="date">Date</label>
            <input type="date" name="date" id="date" class="form-control">
        </div>
        <div class="form-group">
            <label for="status">Statut</label>
            <input type="text" name="status" id="status" class="form-control">
        </div>
        <div class="form-group">
            <label for="items">Éléments de commande</label>
            <div id="items">
                <div class="item">
                    <select name="items[0][product_id]" class="form-control">
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="items[0][quantity]" class="form-control" placeholder="Quantité">
                </div>
            </div>
            <button type="button" id="add-item" class="btn btn-secondary">Ajouter un élément</button>
        </div>
        <button type="submit" class="btn btn-primary">Créer</button>
    </form>
</div>

<script>
    document.getElementById('add-item').addEventListener('click', function() {
        const itemContainer = document.getElementById('items');
        const itemCount = itemContainer.getElementsByClassName('item').length;
        const newItem = document.createElement('div');
        newItem.classList.add('item');
        newItem.innerHTML = `
            <select name="items[${itemCount}][product_id]" class="form-control">
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
            <input type="number" name="items[${itemCount}][quantity]" class="form-control" placeholder="Quantité">
        `;
        itemContainer.appendChild(newItem);
    });
</script>
@endsection
