@extends('layouts.storefront')

@section('title', 'Commande confirmée')
@section('page-title', 'Commande confirmée')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('storefront') }}">Accueil</a></li>
    <li class="breadcrumb-item active">Commande confirmée</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 text-center">
        <div class="card">
            <div class="card-body py-5">
                <div class="mb-4">
                    <i class="fas fa-check-circle fa-5x text-success"></i>
                </div>
                
                <h2 class="mb-3">Merci pour votre commande !</h2>
                
                <p class="lead">
                    Votre commande #{{ $order->id }} a bien été enregistrée.
                </p>
                
                <p>
                    Nous avons envoyé un email de confirmation à <strong>{{ $order->user->email }}</strong>.
                </p>
                
                <div class="mt-5">
                    <a href="{{ route('storefront') }}" class="btn btn-primary">
                        <i class="fas fa-shopping-bag mr-2"></i> Retour à la boutique
                    </a>
                    
                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-secondary ml-2">
                        <i class="fas fa-receipt mr-2"></i> Voir ma commande
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection