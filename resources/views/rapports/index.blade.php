@extends('layouts.app')

@section('title', 'Rapports')

@section('content')
<div class="container mx-auto py-6">
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg">
        <!-- En-tête -->
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-blue-100 dark:bg-blue-900 rounded-t-lg">
            <h3 class="text-xl font-semibold text-blue-700 dark:text-blue-400 flex items-center">
                <i class="fas fa-chart-bar mr-2"></i> Rapports et Analyses
            </h3>
        </div>

        <!-- Contenu -->
        <div class="px-6 py-4">
            <!-- Affichage des indicateurs -->
            <div class="mb-6 grid grid-cols-2 gap-4">
                <div class="bg-green-100 dark:bg-green-900 p-4 rounded-lg">
                    <p class="text-sm text-green-700 dark:text-green-200">Chiffre d'affaires mensuel</p>
                    <p class="text-xl font-semibold text-green-900 dark:text-green-100">{{ number_format($chiffreAffaires, 2) }} €</p>
                </div>
                <div class="bg-red-100 dark:bg-red-900 p-4 rounded-lg">
                    <p class="text-sm text-red-700 dark:text-red-200">Produits en stock critique</p>
                    <p class="text-xl font-semibold text-red-900 dark:text-red-100">{{ $stockCritique }}</p>
                </div>
            </div>

            <!-- Tableau des rapports -->
            <div class="overflow-x-auto">
                <table class="table-auto w-full border-collapse border border-gray-200 dark:border-gray-700 text-sm">
                    <thead>
                        <tr class="bg-blue-50 dark:bg-blue-900">
                            <th class="border px-4 py-2 text-gray-700 dark:text-gray-200 font-semibold text-left">#</th>
                            <th class="border px-4 py-2 text-gray-700 dark:text-gray-200 font-semibold text-left">Nom du Rapport</th>
                            <th class="border px-4 py-2 text-gray-700 dark:text-gray-200 font-semibold text-left">Description</th>
                            <th class="border px-4 py-2 text-gray-700 dark:text-gray-200 font-semibold text-left">Date de Création</th>
                            <th class="border px-4 py-2 text-gray-700 dark:text-gray-200 font-semibold text-left">Transactions</th>
                            <th class="border px-4 py-2 text-gray-700 dark:text-gray-200 font-semibold text-left">Stocks</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($reports as $report)
                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-200">
                            <td class="border px-4 py-2 text-gray-800 dark:text-gray-200">{{ $loop->iteration }}</td>
                            <td class="border px-4 py-2 text-gray-800 dark:text-gray-200 font-medium">
                                <a href="{{ route('rapports.index', $report->id) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ $report->name }}
                                </a>
                            </td>
                            <td class="border px-4 py-2 text-gray-600 dark:text-gray-300">{{ $report->description }}</td>
                            <td class="border px-4 py-2 text-gray-800 dark:text-gray-200">{{ $report->created_at->translatedFormat('d/m/Y') }}</td>
                            <td class="border px-4 py-2 text-gray-800 dark:text-gray-200">
                                @if($report->transactions->isNotEmpty())
                                    {{ $report->transactions->count() }} transactions associées.
                                    <a href="#" class="text-blue-500 dark:text-blue-400 hover:underline" onclick="event.preventDefault(); showModal('transactions-modal-{{ $report->id }}')">Voir détails</a>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">Aucune transaction associée.</span>
                                @endif
                            </td>
                            <td class="border px-4 py-2 text-gray-800 dark:text-gray-200">
                                @if($report->stocks->isNotEmpty())
                                    {{ $report->stocks->count() }} stocks associés.
                                    <a href="#" class="text-blue-500 dark:text-blue-400 hover:underline" onclick="event.preventDefault(); showModal('stocks-modal-{{ $report->id }}')">Voir détails</a>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">Aucun stock associé.</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6 flex justify-end">
                {{ $reports->links() }}
            </div>
        </div>

        <!-- Modals pour afficher les détails -->
        @foreach ($reports as $report)
            <!-- Modal pour les transactions -->
            <div id="transactions-modal-{{ $report->id }}" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50 p-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-3xl max-h-[90vh] overflow-y-auto">
                    <div class="p-6">
                        <h2 class="text-lg font-bold mb-4 text-gray-800 dark:text-gray-100">Transactions associées au {{ $report->name }}</h2>
                        <ul>
                            @foreach ($report->transactions as $transaction)
                                <li class="mb-2 text-gray-800 dark:text-gray-400">
                                    {{ $transaction->type === 'entry' ? 'Entrée' : 'Sortie' }} :
                                    {{ $transaction->quantity }} unités à {{ number_format($transaction->price, 2) }} €
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-4 flex justify-end">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white py-1 px-4 rounded-lg" onclick="hideModal('transactions-modal-{{ $report->id }}')">Fermer</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal pour les stocks -->
            <div id="stocks-modal-{{ $report->id }}" class="hidden fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center z-50 p-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-3xl max-h-[90vh] overflow-y-auto">
                    <div class="p-6">
                        <h2 class="text-lg font-bold mb-4 text-gray-800 dark:text-gray-100">Stocks associés au {{ $report->name }}</h2>
                        <ul>
                            @foreach ($report->stocks as $stock)
                                <li class="mb-2 text-gray-800 dark:text-gray-400">
                                    {{ $stock->type === 'entry' ? 'Ajout' : ($stock->type === 'exit' ? 'Retrait' : 'Ajustement') }} :
                                    {{ $stock->quantity }} unités
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-4 flex justify-end">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white py-1 px-4 rounded-lg" onclick="hideModal('stocks-modal-{{ $report->id }}')">Fermer</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Scripts pour gérer les modals -->
<script>
    function showModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
    }

    function hideModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
    }
</script>
@endsection