@extends('layouts.storefront')

@section('title', 'Finalisation de la commande')
@section('page-title', 'Finalisation de la commande')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('storefront') }}">Accueil</a></li>
    <li class="breadcrumb-item"><a href="{{ route('cart.show') }}">Panier</a></li>
    <li class="breadcrumb-item active">Commande</li>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informations de livraison</h3>
            </div>
            <div class="card-body">
                @guest
                    <div class="alert alert-info">
                        <p>Vous n'êtes pas connecté. Veuillez créer un compte ou vous connecter pour finaliser votre commande.</p>
                        <div class="d-flex justify-content-between mt-3">
                            <a href="{{ route('login') }}" class="btn btn-primary">Se connecter</a>
                            <a href="{{ route('register') }}" class="btn btn-success">Créer un compte</a>
                        </div>
                    </div>
                @endguest

                <form method="POST" action="{{ route('checkout.process') }}">
                    @csrf
                    
                    <div class="form-group">
                        <label for="name">Nom complet</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="{{ Auth::check() ? Auth::user()->name : old('name') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="{{ Auth::check() ? Auth::user()->email : old('email') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="address">Adresse de livraison</label>
                        <textarea class="form-control" id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="phone">Téléphone</label>
                        <input type="text" class="form-control" id="phone" name="phone" 
                               value="{{ Auth::check() ? Auth::user()->phone : old('phone') }}" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg btn-block mt-4">
                        Confirmer la commande
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Récapitulatif</h3>
            </div>
            <div class="card-body">
                @foreach($cart as $item)
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ $item['name'] }} (x{{ $item['quantity'] }})</span>
                        <span>{{ number_format($item['price'] * $item['quantity'], 2) }} €</span>
                    </div>
                @endforeach
                
                <hr>
                
                <div class="d-flex justify-content-between font-weight-bold">
                    <span>Total</span>
                    <span>{{ number_format(array_sum(array_map(function($item) { 
                        return $item['price'] * $item['quantity']; 
                    }, $cart)), 2) }} €</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection