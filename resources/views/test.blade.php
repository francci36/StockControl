<!-- resources/views/test.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Footer</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    
    @include('layouts.footer')
</body>
</html>




<!--

<!-- resources/views/dashboard.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Tableau de bord</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <nav>
        <a href="{{ route('home') }}">Accueil</a>
        <a href="/stocks">Stocks</a>
        <a href="/commandes">Commandes</a>
        <a href="/fournisseurs">Fournisseurs</a>
        <a href="/rapports">Rapports</a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Déconnexion</button>
        </form>
    </nav>

    <div class="container">
        <h1>Tableau de bord</h1>

        <div class="dashboard-section">
            <h2>Niveaux de stock</h2>
            <table>
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Quantité</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Produit A</td>
                        <td>50</td>
                    </tr>
                    <tr>
                        <td>Produit B</td>
                        <td>20</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="dashboard-section">
            <h2>Alertes de réapprovisionnement</h2>
            <table>
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Quantité</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Produit C</td>
                        <td>5</td>
                    </tr>
                    <tr>
                        <td>Produit D</td>
                        <td>2</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="dashboard-section">
            <h2>Commandes récentes</h2>
            <table>
                <thead>
                    <tr>
                        <th>Commande</th>
                        <th>Date</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Commande #123</td>
                        <td>2024-11-01</td>
                        <td>Livrée</td>
                    </tr>
                    <tr>
                        <td>Commande #124</td>
                        <td>2024-11-02</td>
                        <td>En cours</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="dashboard-section">
            <h2>Graphique des stocks</h2>
            <canvas id="stockChart"></canvas>
        </div>
    </div>

    <footer>
        <div class="footer-container">
            <p>&copy; 2024 Votre Entreprise. Tous droits réservés.</p>
            <p>
                <a href="/about">À propos</a> |
                <a href="/contact">Contact</a> |
                <a href="/privacy">Politique de confidentialité</a>
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('stockChart').getContext('2d');
            var stockChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Produit A', 'Produit B', 'Produit C', 'Produit D'],
                    datasets: [{
                        label: 'Quantité',
                        data: [50, 20, 5, 2],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</body>



-->
