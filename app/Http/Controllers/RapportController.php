<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        // Automatisation des rapports
        $this->updateReports();

        // Calcul du chiffre d'affaires mensuel (transactions de type 'exit')
        $chiffreAffaires = Transaction::whereMonth('created_at', now()->month)
            ->where('type', 'exit')
            ->sum(DB::raw('quantity * price'));

        // Calcul du nombre de produits en stock critique (avec seuil configurable)
        $seuilCritique = config('inventory.stock_critique', 10);
        $stockCritique = Stock::where('quantity', '<=', $seuilCritique)->count();

        // Calcul du nombre total de commandes
        $totalCommandes = Order::count();

        // Calcul des commandes par statut
        $commandesParStatut = Order::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get();

        // Calcul des produits en stock (pour le graphique)
        $produitsEnStock = Stock::with('product')
            ->orderBy('quantity', 'desc')
            ->limit(10)
            ->get();

        // Mise à jour des montants totaux pour toutes les commandes
        $orders = Order::with('orderItems')->get();
        foreach ($orders as $order) {
            $order->total_amount = $order->calculateTotalAmount();
            $order->save();
        }

        // Calcul des statistiques des fournisseurs
        $fournisseurs = Supplier::withCount('orders')
            ->withSum('orders', 'total_amount')
            ->get();

        // Pagination des rapports avec les transactions et les stocks associés
        $reports = Report::with([
            'transactions' => function ($query) {
                $query->whereIn('type', ['entry', 'exit']);
            },
            'stocks'
        ])->paginate(10);

        // Passer les données à la vue
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
        $rapportVentes = Report::firstOrCreate([
            'name' => 'Rapport des Ventes',
            'description' => 'Analyse des ventes mensuelles',
        ], [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $rapportStocks = Report::firstOrCreate([
            'name' => 'Rapport des Stocks',
            'description' => 'État des stocks actuels',
        ], [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

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
