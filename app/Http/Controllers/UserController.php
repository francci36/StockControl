<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Product;

class UserController extends Controller
{
    public function dashboard()
    {
        if (auth()->user()->role !== 'user') {
            abort(403, 'Accès interdit.');
        }

        $orders = Auth::user()->orders;
        return view('user.dashboard', compact('orders'));
    }

    // Nouvelle méthode pour la page d'accueil utilisateur
    public function home()
    {
        if (auth()->user()->role !== 'user') {
            abort(403, 'Accès interdit.');
        }

        // Récupérer le nombre total de commandes de l'utilisateur
        $totalOrders = Order::where('user_id', auth()->id())->count();

        // Récupérer le nombre total de transactions
        $totalTransactions = \App\Models\Transaction::whereHas('sale', function ($query) {
            $query->where('user_id', auth()->id());
        })->count();
        
        // Trouver les produits avec un stock faible
        $lowStockItems = Product::whereColumn('quantity', '<=', 'stock_threshold')->count();

        return view('user.home', compact('totalOrders', 'totalTransactions', 'lowStockItems'));
    }
}

