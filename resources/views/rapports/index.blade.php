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
            <!-- Section des indicateurs -->
            <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Chiffre d'affaires mensuel -->
                <div class="bg-green-100 dark:bg-green-900 p-4 rounded-lg">
                    <p class="text-sm text-green-700 dark:text-green-200">Chiffre d'affaires mensuel</p>
                    <p class="text-xl font-semibold text-green-900 dark:text-green-100">
                        {{ number_format($chiffreAffaires, 2) }} €
                    </p>
                </div>

                <!-- Produits en stock critique -->
                <div class="bg-red-100 dark:bg-red-900 p-4 rounded-lg">
                    <p class="text-sm text-red-700 dark:text-red-200">Produits en stock critique</p>
                    <p class="text-xl font-semibold text-red-900 dark:text-red-100">{{ $stockCritique }}</p>
                </div>

                <!-- Nombre total de commandes -->
                <div class="bg-blue-100 dark:bg-blue-900 p-4 rounded-lg">
                    <p class="text-sm text-blue-700 dark:text-blue-200">Nombre total de commandes</p>
                    <p class="text-xl font-semibold text-blue-900 dark:text-blue-100">{{ $totalCommandes }}</p>
                </div>
            </div>

            <!-- Section des graphiques -->
            <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-600 mb-4">Commandes par statut</h4>
                    <canvas id="commandesParStatutChart" width="400" height="200"></canvas>
                </div>
                <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                    <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-600 mb-4">Produits en stock</h4>
                    <canvas id="produitsEnStockChart" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- Section des fournisseurs -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-600 mb-4">Statistiques des fournisseurs</h4>
                <div class="overflow-x-auto">
                    <table class="table-auto w-full border-collapse border border-gray-200 dark:border-gray-700 text-sm">
                        <thead>
                            <tr class="bg-blue-50 dark:bg-blue-900">
                                <th class="border px-4 py-2 text-gray-700 dark:text-gray-200 font-semibold text-left">Fournisseur</th>
                                <th class="border px-4 py-2 text-gray-700 dark:text-gray-200 font-semibold text-left">Nombre de commandes</th>
                                <th class="border px-4 py-2 text-gray-700 dark:text-gray-200 font-semibold text-left">Montant total des achats</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($fournisseurs as $fournisseur)
                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-200">
                                <td class="border px-4 py-2 text-gray-800 dark:text-gray-200">{{ $fournisseur->name }}</td>
                                <td class="border px-4 py-2 text-gray-800 dark:text-gray-200">{{ $fournisseur->orders_count }}</td>
                                <td class="border px-4 py-2 text-gray-800 dark:text-gray-200">{{ number_format($fournisseur->orders_sum_total_amount, 2) }} €</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-gray-500">Aucune donnée disponible</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Scripts pour les graphiques -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Check if Chart.js is loaded
    if (typeof Chart === "undefined") {
        console.error("Chart.js is not loaded.");
    }

    // Get the current theme (light or dark)
    const isDarkMode = document.documentElement.classList.contains('dark');
    const canvasBackgroundColor = isDarkMode ? '#d3d3d3' : '#ffffff'; // Light grey for dark mode, white for light mode.
    const fontColor = isDarkMode ? '#d3d3d3' : '#000000'; // Adjust font colors accordingly.

    // Graphique des commandes par statut
    const commandesParStatutElement = document.getElementById('commandesParStatutChart');
    if (commandesParStatutElement) {
        const commandesParStatutCtx = commandesParStatutElement.getContext('2d');
        commandesParStatutElement.style.backgroundColor = canvasBackgroundColor; // Set the canvas background dynamically.

        new Chart(commandesParStatutCtx, {
            type: 'pie',
            data: {
                labels: {!! json_encode($commandesParStatut->pluck('status')) !!} || [],
                datasets: [{
                    data: {!! json_encode($commandesParStatut->pluck('count')) !!} || [],
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            font: {
                                size: 14,
                                family: 'Arial',
                                color: fontColor, // Adjust legend font color.
                            },
                        },
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                return `${label}: ${value} commandes`;
                            }
                        }
                    },
                },
                animation: {
                    animateScale: true,
                    animateRotate: true,
                },
            },
        });
    } else {
        console.warn("Element with ID 'commandesParStatutChart' not found.");
    }

    // Graphique des produits en stock
    const produitsEnStockElement = document.getElementById('produitsEnStockChart');
    if (produitsEnStockElement) {
        const produitsEnStockCtx = produitsEnStockElement.getContext('2d');
        produitsEnStockElement.style.backgroundColor = canvasBackgroundColor; // Set the canvas background dynamically.

        new Chart(produitsEnStockCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($produitsEnStock->pluck('product.name')) !!} || [],
                datasets: [{
                    label: 'Quantité en stock',
                    data: {!! json_encode($produitsEnStock->pluck('quantity')) !!} || [],
                    backgroundColor: ['#4CAF50', '#FF5722', '#FFC107', '#03A9F4', '#9C27B0'],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: {
                                size: 12,
                                family: 'Arial',
                                color: fontColor, // Adjust legend font color.
                            },
                        },
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Quantité: ${context.raw}`;
                            }
                        }
                    },
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Produits',
                            font: {
                                size: 14,
                                family: 'Arial',
                                color: fontColor, // Adjust font color for x-axis.
                            },
                        },
                        grid: {
                            borderColor: isDarkMode ? '#444444' : '#cccccc', // Gridline colors for x-axis.
                        },
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Quantité',
                            font: {
                                size: 14,
                                family: 'Arial',
                                color: fontColor, // Adjust font color for y-axis.
                            },
                        },
                        grid: {
                            color: isDarkMode ? '#444444' : '#f0f0f0', // Gridline colors for y-axis.
                        },
                    },
                },
                animation: {
                    duration: 1500,
                    easing: 'easeOutBounce',
                },
            },
        });
    } else {
        console.warn("Element with ID 'produitsEnStockChart' not found.");
    }
</script>


@endsection