<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        // Récupérer les enregistrements de la table stocks avec les produits associés et ajouter la pagination
        $stocks = Stock::with('product')->paginate(10); // Remplacez 10 par le nombre d'éléments à afficher par page

        // Log pour vérifier les données
        Log::info('Stocks récupérés : ', $stocks->items());

        


        // Passer les données à la vue
        return view('stocks.index', compact('stocks'));
    }

    public function update(Request $request, Stock $stock)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $stock->update($validated);
        return redirect()->route('stocks.index')->with('success', 'Stock mis à jour');
    }
}
