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
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-line mr-1"></i>
                                Graphique des transactions
                            </h3>
                        </div>
                        <div class="card-body">
                            <canvas id="transactionsChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
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
    // Données initiales pour les graphiques
    let stockData = @json($stockData); // Assurez-vous que $stockData est bien défini côté serveur
    let labels = stockData.map(data => data.produit);
    let quantities = stockData.map(data => data.quantite);

    // Fonction pour générer une couleur en fonction de la quantité
    const getColor = (quantity) => {
        if (quantity < 4) {
            return 'rgba(255, 99, 132, 0.9)'; // Rouge : stock critique
        } else if (quantity >= 4 && quantity < 10) {
            return 'rgba(255, 206, 86, 0.9)'; // Jaune : stock moyen
        } else {
            return 'rgba(75, 192, 192, 0.9)'; // Bleu : stock élevé
        }
    };

    // Générer les couleurs dynamiques en fonction des quantités
    let backgroundColors = quantities.map(getColor);

    // Graphique des stocks (barres)
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
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                }
            }
        }
    });

    // Graphique de répartition des stocks (camembert)
    const ctxPie = document.getElementById('pieChart').getContext('2d');
    const pieChart = new Chart(ctxPie, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                label: 'Répartition des stocks',
                data: quantities,
                backgroundColor: backgroundColors,
                borderColor: 'rgba(255, 255, 255, 0.8)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });

    // Fonction pour mettre à jour les graphiques avec les nouvelles données du serveur
    function updateCharts() {
        fetch('/dashboard/stock-data')
            .then(response => response.json())
            .then(data => {
                const newLabels = data.labels;
                const newQuantities = data.quantities;

                // Mettre à jour le graphique des stocks
                stockChart.data.labels = newLabels;
                stockChart.data.datasets[0].data = newQuantities;
                stockChart.data.datasets[0].backgroundColor = newQuantities.map(getColor);
                stockChart.update();

                // Mettre à jour le graphique en camembert
                pieChart.data.labels = newLabels;
                pieChart.data.datasets[0].data = newQuantities;
                pieChart.data.datasets[0].backgroundColor = newQuantities.map(getColor);
                pieChart.update();
            })
            .catch(error => console.error('Erreur lors de la mise à jour des graphiques :', error));
    }

    // Initialisation du graphique des transactions
    const ctxTransactions = document.getElementById('transactionsChart').getContext('2d');
    const transactionsChart = new Chart(ctxTransactions, {
        type: 'line',
        data: {
            labels: [], // Les dates des transactions
            datasets: [{
                label: 'Nombre de transactions',
                data: [], // Le nombre de transactions par date
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Fonction pour mettre à jour le graphique des transactions
    function updateTransactionsChart() {
        fetch('/dashboard/transactions-data')
            .then(response => response.json())
            .then(data => {
                // Mettre à jour les données du graphique
                transactionsChart.data.labels = data.map(transaction => transaction.date);
                transactionsChart.data.datasets[0].data = data.map(transaction => transaction.count);
                transactionsChart.update();
            })
            .catch(error => console.error('Erreur lors de la mise à jour du graphique des transactions :', error));
    }

    // Rafraîchir les données toutes les 5 secondes (optionnel)
    setInterval(updateCharts, 5000);
    setInterval(updateTransactionsChart, 5000);

    // Gestion du filtre par période
    const filterElement = document.getElementById('filter');
    if (filterElement) {
        filterElement.addEventListener('change', function () {
            const days = this.value;
            fetch(`/dashboard/stock-data?days=${days}`)
                .then(response => response.json())
                .then(data => {
                    const newLabels = data.labels;
                    const newQuantities = data.quantities;

                    stockChart.data.labels = newLabels;
                    stockChart.data.datasets[0].data = newQuantities;
                    stockChart.data.datasets[0].backgroundColor = newQuantities.map(getColor);
                    stockChart.update();

                    pieChart.data.labels = newLabels;
                    pieChart.data.datasets[0].data = newQuantities;
                    pieChart.data.datasets[0].backgroundColor = newQuantities.map(getColor);
                    pieChart.update();
                });
        });
    }

    // Exportation du graphique des stocks en image
    const exportButton = document.getElementById('exportChart');
    if (exportButton) {
        exportButton.addEventListener('click', function () {
            const link = document.createElement('a');
            link.href = stockChart.toBase64Image();
            link.download = 'graphique_stocks.png';
            link.click();
        });
    }
});
</script>
@endsection