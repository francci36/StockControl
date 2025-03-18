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
        // Calcul du chiffre d'affaires mensuel (uniquement les transactions de type 'exit')
        $chiffreAffaires = Transaction::whereMonth('created_at', now()->month)
            ->where('type', 'exit')
            ->sum(DB::raw('quantity * price'));

        // Calcul du nombre de produits en stock critique (avec seuil configurable)
        $seuilCritique = config('inventory.stock_critique', 10);
        $stockCritique = Stock::where('quantity', '<=', $seuilCritique)->count();

        // Pagination des rapports avec les transactions associées
        $reports = Report::with(['transactions' => function ($query) {
            $query->whereIn('type', ['entry', 'exit']); // Filtrer par type
        }])->paginate(10);

        // Passer les données à la vue
        return view('rapports.index', compact('chiffreAffaires', 'stockCritique', 'reports'));
    }
}
