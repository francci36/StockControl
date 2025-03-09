@extends('layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tableau de bord</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <!-- Petites boîtes de statistiques -->
            <div class="row">
                <!-- Fournisseurs -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $totalSuppliers }}</h3>
                            <p>Fournisseurs</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-truck"></i>
                        </div>
                        <a href="{{ route('suppliers.index') }}" class="small-box-footer">
                            Plus d'infos <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Produits en stock -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $totalProducts }}</h3>
                            <p>Produits en stock</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <a href="{{ route('products.index') }}" class="small-box-footer">
                            Plus d'infos <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Commandes en attente -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $pendingOrders }}</h3>
                            <p>Commandes en attente</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <a href="{{ route('orders.index') }}" class="small-box-footer">
                            Plus d'infos <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>

                <!-- Produits à réapprovisionner -->
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ count($lowStock) }}</h3>
                            <p>Produits à réapprovisionner</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <a href="{{ route('products.index') }}" class="small-box-footer">
                            Plus d'infos <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filtres -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-filter mr-1"></i>
                                Filtres
                            </h3>
                        </div>
                        <div class="card-body">
                            <select id="filter" class="form-control">
                                <option value="7">7 derniers jours</option>
                                <option value="30">30 derniers jours</option>
                                <option value="90">90 derniers jours</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graphiques -->
            <div class="row">
                <!-- Graphique des stocks -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-th mr-1"></i>
                                Graphique des stocks
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button id="exportChart" class="btn btn-tool">
                                    <i class="fas fa-download"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="stockChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Graphique en camembert -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-1"></i>
                                Répartition des stocks
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graphique des transactions -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line mr-1"></i>
                                Graphique des transactions
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="transactionsChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts pour les graphiques -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment/dist/chartjs-adapter-moment.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Données du graphique des stocks
        let stockData = @json($stockData); // Assurez-vous que $stockData est bien défini côté serveur
        let labels = stockData.map(data => data.produit);
        let quantities = stockData.map(data => data.quantite);

        // Fonction pour générer une couleur en fonction de la quantité
        const getColor = (quantity) => {
            if (quantity < 4) {
                return 'rgba(255, 99, 132, 0.9)'; // Rouge pour les stocks critiques
            } else if (quantity >= 4 && quantity < 10) {
                return 'rgba(255, 206, 86, 0.9)'; // Jaune pour les stocks moyens
            } else {
                return 'rgba(75, 192, 192, 0.9)'; // Bleu pour les stocks élevés
            }
        };

        // Appliquer les couleurs dynamiques
        let backgroundColors = quantities.map(getColor);

        // Générer le graphique des stocks
        const ctxStock = document.getElementById('stockChart').getContext('2d');
        const stockChart = new Chart(ctxStock, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Quantité en stock',
                    data: quantities,
                    backgroundColor: backgroundColors,
                    borderColor: 'rgba(60,141,188,0.8)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1000,
                    easing: 'easeInOutQuad'
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: (context) => {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const maxQuantity = Math.max(...quantities);
                                const percentage = ((value / maxQuantity) * 100).toFixed(2);
                                return `${label}: ${value} unités (${percentage}%)`;
                            }
                        }
                    },
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            color: '#333',
                            font: {
                                size: 14
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(255, 255, 255, 0.2)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(255, 255, 255, 0.2)'
                        }
                    }
                }
            }
        });

        // Graphique en camembert
        const ctxPie = document.getElementById('pieChart').getContext('2d');
        const pieChart = new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: ['Catégorie A', 'Catégorie B', 'Catégorie C'],
                datasets: [{
                    label: 'Répartition des stocks',
                    data: [30, 50, 20],
                    backgroundColor: ['rgba(255, 99, 132, 0.9)', 'rgba(75, 192, 192, 0.9)', 'rgba(255, 206, 86, 0.9)'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });

        // Graphique des transactions
        const ctxTransactions = document.getElementById('transactionsChart').getContext('2d');
        const transactionsChart = new Chart(ctxTransactions, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Transactions',
                    data: [12, 19, 3, 5, 2, 3],
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    fill: false
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Filtre
        document.getElementById('filter').addEventListener('change', function () {
            const days = this.value;
            fetch(`/dashboard/data?days=${days}`)
                .then(response => response.json())
                .then(data => {
                    stockChart.data.labels = data.labels;
                    stockChart.data.datasets[0].data = data.quantities;
                    stockChart.update();
                });
        });

        // Exportation du graphique
        document.getElementById('exportChart').addEventListener('click', function () {
            const link = document.createElement('a');
            link.href = stockChart.toBase64Image();
            link.download = 'graphique_stocks.png';
            link.click();
        });
    });
</script>
@endsection