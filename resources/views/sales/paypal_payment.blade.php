<!-- resources/views/sales/paypal_payment.blade.php -->
@extends('layouts.app')

@section('content')
    <h2>Paiement PayPal en cours...</h2>
    
    <p>Merci pour votre achat ! Votre paiement via PayPal est en cours de traitement.</p>
    
    <a href="{{ route('sales.store') }}">Retour aux ventes</a>
@endsection
