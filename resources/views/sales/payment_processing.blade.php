@extends('layouts.app')

@section('title', 'Paiement en cours')

@section('content')
<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg text-center">
    <div class="mb-6">
        <i class="fas fa-spinner fa-spin text-blue-500 text-4xl mb-4"></i>
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">
            Traitement de votre paiement
        </h2>
        <p class="text-gray-600 dark:text-gray-400 mt-2">
            Veuillez patienter pendant que nous traitons votre paiement...
        </p>
    </div>

    <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg mb-6">
        <p class="text-gray-800 dark:text-gray-200">Référence: #{{ $sale->id }}</p>
        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-2">
            {{ number_format($sale->total_amount, 2, ',', ' ') }} €
        </p>
        <p class="text-gray-600 dark:text-gray-400 mt-2">
            Mode de paiement: {{ ucfirst(str_replace('_', ' ', $payment_type)) }}
        </p>
    </div>

    <script>
        // Simulation de traitement - en réel vous utiliseriez des webhooks
        setTimeout(function() {
            window.location.href = "{{ route('sales.show', $sale->id) }}";
        }, 3000);
    </script>
</div>
@endsection