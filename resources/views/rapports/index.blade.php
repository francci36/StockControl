@extends('layouts.app')

@section('title', 'Rapports')

@section('content')
<div class="container mx-auto py-6">
    <div class="bg-white shadow-lg rounded-lg">
        <!-- En-tête -->
        <div class="px-6 py-4 border-b border-gray-200 bg-blue-100 rounded-t-lg">
            <h3 class="text-xl font-semibold text-blue-700 flex items-center">
                <i class="fas fa-chart-bar mr-2"></i> Rapports et Analyses
            </h3>
        </div>

        <!-- Contenu -->
        <div class="px-6 py-4">
            <!-- Affichage des indicateurs -->
            <div class="mb-6 grid grid-cols-2 gap-4">
                <div class="bg-green-100 p-4 rounded-lg">
                    <p class="text-sm text-green-700">Chiffre d'affaires mensuel</p>
                    <p class="text-xl font-semibold text-green-900">{{ number_format($chiffreAffaires, 2) }} €</p>
                </div>
                <div class="bg-red-100 p-4 rounded-lg">
                    <p class="text-sm text-red-700">Produits en stock critique</p>
                    <p class="text-xl font-semibold text-red-900">{{ $stockCritique }}</p>
                </div>
            </div>

            <!-- Tableau des rapports -->
            <div class="overflow-x-auto">
                <table class="table-auto w-full border-collapse border border-gray-200 text-sm">
                    <thead>
                        <tr class="bg-blue-50">
                            <th class="border px-4 py-2 text-gray-700 font-semibold text-left">#</th>
                            <th class="border px-4 py-2 text-gray-700 font-semibold text-left">Nom du Rapport</th>
                            <th class="border px-4 py-2 text-gray-700 font-semibold text-left">Description</th>
                            <th class="border px-4 py-2 text-gray-700 font-semibold text-left">Date de Création</th>
                            <th class="border px-4 py-2 text-gray-700 font-semibold text-left">Transactions</th>
                            <th class="border px-4 py-2 text-gray-700 font-semibold text-left">Stocks</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($reports as $report)
                        <tr class="hover:bg-gray-100 transition duration-200">
                            <td class="border px-4 py-2 text-gray-800">{{ $loop->iteration }}</td>
                            <td class="border px-4 py-2 text-gray-800 font-medium">
                                <a href="{{ route('rapports.index', $report->id) }}" class="text-blue-600 hover:underline">
                                    {{ $report->name }}
                                </a>
                            </td>
                            <td class="border px-4 py-2 text-gray-600">{{ $report->description }}</td>
                            <td class="border px-4 py-2 text-gray-800">{{ $report->created_at->translatedFormat('d/m/Y') }}</td>
                            <td class="border px-4 py-2 text-gray-800">
                                <ul>
                                    @forelse ($report->transactions as $transaction)
                                        <li>
                                            {{ $transaction->type === 'entry' ? 'Entrée' : 'Sortie' }} :
                                            {{ $transaction->quantity }} unités à {{ number_format($transaction->price, 2) }} €
                                        </li>
                                    @empty
                                        <li class="text-gray-500">Aucune transaction associée.</li>
                                    @endforelse
                                </ul>
                            </td>
                            <td class="border px-4 py-2 text-gray-800">
                                <ul>
                                    @forelse ($report->stocks as $stock)
                                        <li>
                                            {{ $stock->type }} : {{ $stock->quantity }} unités
                                        </li>
                                    @empty
                                        <li class="text-gray-500">Aucun stock associé.</li>
                                    @endforelse
                                </ul>
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
    </div>
</div>
@endsection