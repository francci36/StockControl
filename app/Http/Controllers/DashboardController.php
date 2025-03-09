<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Récupérer les données des transactions agrégées
        $transactions = Transaction::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                                    ->groupBy('date')
                                    ->get();

        // Récupérer les comptes et enregistrements divers
        $totalSuppliers = Supplier::count();
        $totalProducts = Product::count();
        $pendingOrders = Order::where('status', 'pending')->count(); // Ensure consistent status check
        $lowStock = Product::where('stock', '<', 10)->get();

        // Récupérer les commandes récentes avec les données associées de fournisseurs et produits
        $recentOrders = Order::with('supplier', 'items.product')->latest()->take(10)->get(); // Ensure consistent number of recent orders

        // Récupérer les données de stock
        $stockData = Product::select('name as produit', 'stock as quantite')->get();

        return view('dashboard.index', compact(
            'totalSuppliers', 'totalProducts', 'pendingOrders', 'lowStock', 'recentOrders', 'stockData', 'transactions'
        ));
    }

    public function transactionsData()
    {
        // Récupérer les données des transactions agrégées
        $transactions = Transaction::selectRaw('DATE(created_at) as date, COUNT(*) as count')
                                    ->groupBy('date')
                                    ->get();

        return response()->json($transactions);
    }
}
