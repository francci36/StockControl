<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Stock;
use App\Models\Report;
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

        // Pagination des rapports avec les transactions et les stocks associés
        $reports = Report::with([
            'transactions' => function ($query) {
                $query->whereIn('type', ['entry', 'exit']); // Inclure les deux types de transactions
            },
            'stocks'
        ])->paginate(10);

        // Passer les données à la vue
        return view('rapports.index', compact('chiffreAffaires', 'stockCritique', 'reports'));
    }

    private function updateReports()
    {
        // Récupérer ou créer le rapport des ventes
        $rapportVentes = Report::firstOrCreate([
            'name' => 'Rapport des Ventes',
            'description' => 'Analyse des ventes mensuelles',
        ], [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Récupérer ou créer le rapport des stocks
        $rapportStocks = Report::firstOrCreate([
            'name' => 'Rapport des Stocks',
            'description' => 'État des stocks actuels',
        ], [
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Associer les transactions de type 'exit' au rapport des ventes
        Transaction::where('type', 'exit')
            ->whereNull('report_id') // Associer uniquement les transactions sans rapport
            ->update(['report_id' => $rapportVentes->id]);

        // Associer les transactions de type 'entry' au rapport des stocks
        Transaction::where('type', 'entry')
            ->whereNull('report_id') // Associer uniquement les transactions sans rapport
            ->update(['report_id' => $rapportStocks->id]);

        // Associer les stocks critiques au rapport des stocks
        $seuilCritique = config('inventory.stock_critique', 10);
        Stock::where('quantity', '<=', $seuilCritique)
            ->whereNull('report_id') // Éviter les doublons
            ->update(['report_id' => $rapportStocks->id]);
    }
}
