<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\Stock;
use App\Models\Sale;

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
        if (!auth()->check()) {
            abort(403, 'Accès interdit.');
        }

        // Si l'utilisateur est un user, le rediriger vers la page d'accueil utilisateur
        if (auth()->user()->role === 'user') {
            return redirect()->route('user.home');
        }

        // Récupération des données du tableau de bord pour Admin et Manager
        $transactions = Transaction::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        $totalSuppliers = Supplier::count();
        $totalProducts = Product::count();
        $pendingOrders = Order::where('status', 'pending')->count();

        $lowStock = Product::whereHas('stock', function ($query) {
            $query->whereColumn('quantity', '<=', 'stock_threshold')
                  ->where('quantity', '>', 0); // Exclure les produits sans stock
        })->get();
        


        $recentOrders = Order::with(['supplier', 'products'])
            ->latest()
            ->take(10)
            ->get();

        $stockData = Product::select('name as produit', 'quantity as quantite')->get();
        $totalSales = Sale::count();
        $totalSalesAmount = Sale::sum('total_price');

        return view('dashboard.index', compact(
            'totalSuppliers',
            'totalProducts',
            'pendingOrders',
            'lowStock',
            'recentOrders',
            'transactions',
            'stockData',
            'totalSales',
            'totalSalesAmount'
        ));
    }



    /**
     * Renvoie les données des transactions pour les graphiques.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function transactionsData()
    {
        $transactions = Transaction::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Vérifie si aucune donnée n'est trouvée
        if ($transactions->isEmpty()) {
            return response()->json(['message' => 'Aucune donnée de transaction trouvée'], 404);
        }

        return response()->json($transactions);
    }

    /**
     * Renvoie les données générales du tableau de bord via AJAX.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDashboardData()
    {
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $totalProducts = Product::count();

        // Produits avec stock faible
        $lowStockCount = Product::whereNotNull('stock_threshold')
            ->whereColumn('quantity', '<', 'stock_threshold')
            ->orWhere('quantity', 0)
            ->count();

        $lowStockProducts = Product::whereNotNull('stock_threshold')
            ->whereColumn('quantity', '<', 'stock_threshold')
            ->orWhere('quantity', 0)
            ->get()
            ->map(function ($product) {
                return [
                    'produit' => $product->name,
                    'quantite' => $product->quantity,
                ];
            });

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
        $stocks = Stock::with('product')->get();

        $labels = $stocks->pluck('product.name')->map(function ($name) {
            return $name ?? 'Produit inconnu';
        });

        $quantities = $stocks->pluck('quantity');

        return response()->json([
            'labels' => $labels,
            'quantities' => $quantities,
        ]);
    }
}
