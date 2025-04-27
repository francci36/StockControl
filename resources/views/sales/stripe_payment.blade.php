@extends('layouts.app')

@section('title', 'Paiement par Stripe')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
    <h2 class="text-2xl font-semibold mb-6 text-blue-600 dark:text-blue-400">
        Paiement sécurisé par Stripe
    </h2>

    <div class="mb-6">
        <p class="text-gray-800 dark:text-gray-200 mb-2">
            Montant à payer: <span class="font-bold">{{ number_format($amount, 2, ',', ' ') }} €</span>
        </p>
        <p class="text-gray-600 dark:text-gray-400">
            Référence: #{{ $sale->id }}
        </p>
    </div>

    <form id="stripe-payment-form">
        <div id="card-element" class="p-3 border rounded-lg mb-4"></div>
        <div id="card-errors" role="alert" class="text-red-500 mb-4"></div>
        
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg w-full">
            Payer avec Stripe
        </button>
    </form>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{{ $stripe_key }}');
    const elements = stripe.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#card-element');

    const form = document.getElementById('stripe-payment-form');
    const cardErrors = document.getElementById('card-errors');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const { error, paymentMethod } = await stripe.createPaymentMethod({
            type: 'card',
            card: cardElement,
        });

        if (error) {
            cardErrors.textContent = error.message;
        } else {
            // Envoyer le paymentMethod.id à votre serveur
            const response = await fetch("{{ route('sales.stripeCallback', $sale->id) }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    payment_method_id: paymentMethod.id
                })
            });

            const result = await response.json();
            
            if (result.success) {
                window.location.href = "{{ route('sales.show', $sale->id) }}";
            } else {
                cardErrors.textContent = result.message || 'Erreur lors du paiement';
            }
        }
    });
</script>
@endsection