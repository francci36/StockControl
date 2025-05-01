<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Stock;
use App\Models\Report;
use App\Models\Order;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

class RapportController extends Controller
{
    public function index()
{
    if (!auth()->check() || auth()->user()->role === 'user') {
        abort(403, 'Accès interdit.');
    }
    // Automatisation des rapports
    $this->updateReports();

    // Calcul du chiffre d'affaires mensuel
    $chiffreAffaires = Transaction::whereMonth('created_at', now()->month)
        ->where('type', 'exit')
        ->sum(DB::raw('quantity * price'));

    // Calcul du stock critique
    $seuilCritique = config('inventory.stock_critique', 10);
    $stockCritique = Stock::where('quantity', '<=', $seuilCritique)->count();

    // Nombre total de commandes
    $totalCommandes = Order::count();

    // Calcul des commandes par statut
    $commandesParStatut = Order::selectRaw('status, count(*) as count')
        ->groupBy('status')
        ->get();

    // Produits en stock
    $produitsEnStock = Stock::with('product')
        ->orderBy('quantity', 'desc')
        ->limit(10)
        ->get();

    // Mise à jour des montants totaux des commandes
    $orders = Order::with('orderItems')->get();
    foreach ($orders as $order) {
        if ($order->orderItems->count() > 0) {
            $order->total_amount = $order->calculateTotalAmount();
            $order->save();
        }
    }

    // Statistiques des fournisseurs (ajout des commandes et des montants totaux par fournisseur)
    $fournisseurs = Supplier::withCount('orders') // Ajout du nombre de commandes
        ->withSum('orders', 'total_amount') // Somme des montants des commandes
        ->get();

    // Pagination des rapports
    $reports = Report::with([
        'transactions' => function ($query) {
            $query->whereIn('type', ['entry', 'exit']);
        },
        'stocks'
    ])->paginate(10);

    return view('rapports.index', compact(
        'chiffreAffaires',
        'stockCritique',
        'totalCommandes',
        'commandesParStatut',
        'produitsEnStock',
        'fournisseurs',
        'reports'
    ));
}


    private function updateReports()
    {
        $rapportVentes = Report::updateOrCreate(
            ['name' => 'Rapport des Ventes'],
            [
                'description' => 'Analyse des ventes mensuelles',
                'updated_at' => now()
            ]
        );

        $rapportStocks = Report::updateOrCreate(
            ['name' => 'Rapport des Stocks'],
            [
                'description' => 'État des stocks actuels',
                'updated_at' => now()
            ]
        );

        Transaction::where('type', 'exit')
            ->whereNull('report_id')
            ->update(['report_id' => $rapportVentes->id]);

        Transaction::where('type', 'entry')
            ->whereNull('report_id')
            ->update(['report_id' => $rapportStocks->id]);

        $seuilCritique = config('inventory.stock_critique', 10);
        Stock::where('quantity', '<=', $seuilCritique)
            ->whereNull('report_id')
            ->update(['report_id' => $rapportStocks->id]);
    }
}