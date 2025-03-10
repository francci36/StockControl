<?php

// app/Http/Controllers/RapportController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Stock;
use Illuminate\Support\Facades\DB; // Importez la façade DB

class RapportController extends Controller
{
    public function index()
    {
        // Calcul du chiffre d'affaires mensuel
        $chiffreAffaires = Transaction::whereMonth('created_at', now()->month)
            ->sum(DB::raw('quantity * price')); // Somme des (quantité * prix)

        // Calcul du nombre de produits en stock critique
        $stockCritique = Stock::where('quantity', '<=', 10) // Exemple : stock critique si <= 10
            ->count();

        // Passer les données à la vue
        return view('rapports.index', [
            'chiffreAffaires' => $chiffreAffaires,
            'stockCritique' => $stockCritique,
        ]);
    }
}

