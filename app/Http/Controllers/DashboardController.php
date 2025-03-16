<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\Stock;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Affiche la page du tableau de bord avec les statistiques et les graphiques.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Récupération des données des transactions agrégées par date
        $transactions = Transaction::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Statistiques générales
        $totalSuppliers = Supplier::count();
        $totalProducts = Product::count();
        $pendingOrders = Order::where('status', 'pending')->count();

        // Produits avec un stock faible
        $lowStock = Product::whereNotNull('stock_threshold')
            ->where('stock_threshold', '>=', 0)
            ->where(function ($query) {
                $query->where('quantity', '<', 'stock_threshold')
                    ->orWhere('quantity', 0);
            })
            ->get();

        // Commandes récentes
        $recentOrders = Order::with(['supplier', 'products'])
            ->latest()
            ->take(10)
            ->get();

        // Données pour les graphiques
        $stockData = Product::select('name as produit', 'quantity as quantite')->get();

        return view('dashboard.index', compact(
            'totalSuppliers',
            'totalProducts',
            'pendingOrders',
            'lowStock',
            'recentOrders',
            'transactions',
            'stockData' // Assurez-vous que cette variable est bien passée
        ));
    }
    /**
     * Renvoie les données des transactions pour les graphiques.
     *
     * @return \Illuminate\Http\JsonResponse
     */
        public function transactionsData()
    {
        // Récupérer les données des transactions agrégées par date
        $transactions = Transaction::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Valider que les données ne sont pas vides
        if ($transactions->isEmpty()) {
            return response()->json(['message' => 'Aucune donnée de transaction trouvée'], 404);
        }

        return response()->json($transactions);
    }

    /**
     * Renvoie les données du tableau de bord pour les requêtes AJAX.
     *
     * @return \Illuminate\Http\JsonResponse
     */
        public function getDashboardData()
    {
        // Compter les commandes en cours
        $pendingOrders = Order::where('status', 'pending')->count();

        // Compter les commandes terminées
        $completedOrders = Order::where('status', 'completed')->count();

        // Compter le nombre total de produits
        $totalProducts = Product::count();

        // Produits avec stock faible
        $lowStockCount = Product::whereNotNull('stock_threshold')
            ->where('stock_threshold', '>=', 0)
            ->where(function ($query) {
                $query->whereColumn('quantity', '<', 'stock_threshold') // Utilisation de whereColumn
                    ->orWhere('quantity', 0);
            })
            ->count();

        // Détail des produits avec stock faible
        $lowStockProducts = Product::whereNotNull('stock_threshold')
            ->where('stock_threshold', '>=', 0)
            ->where(function ($query) {
                $query->whereColumn('quantity', '<', 'stock_threshold') // Utilisation de whereColumn
                    ->orWhere('quantity', 0);
            })
            ->get()
            ->map(function ($product) {
                return [
                    'produit' => $product->name,
                    'quantite' => $product->quantity,
                ];
            });

        // Préparation des données pour la réponse JSON
        return response()->json([
            'pendingOrders' => $pendingOrders,
            'completedOrders' => $completedOrders,
            'totalProducts' => $totalProducts,
            'lowStockCount' => $lowStockCount,
            'lowStockProducts' => $lowStockProducts,
        ]);
    }

    /**
     * Renvoie les données de stock pour les graphiques.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStockData()
    {
        // Récupérer les données de la table stocks avec les produits associés
        $stocks = Stock::with('product')->get();

        // Préparer les données pour le graphique
        $labels = $stocks->pluck('product.name')->map(function ($name) {
            return $name ?? 'Produit inconnu'; // Gérer les produits sans nom
        });
        $quantities = $stocks->pluck('quantity');

        return response()->json([
            'labels' => $labels,
            'quantities' => $quantities,
        ]);
    }
}