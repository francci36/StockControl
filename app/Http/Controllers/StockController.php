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
        $stocks = Stock::with('product')->paginate(20); // Remplacez 10 par le nombre d'éléments à afficher par page

        // Log pour vérifier les données
        Log::info('Stocks récupérés : ', $stocks->items());

        


        // Passer les données à la vue
        return view('stocks.index', compact('stocks'));
    }

    public function update(Request $request, Stock $stock)
    {
        // Validation des données
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        // Vérifier si la quantité demandée dépasse le stock actuel
        if ($validated['quantity'] > $stock->quantity) {
            return redirect()->route('stocks.index')->withErrors([
                'quantity' => 'La quantité mise à jour ne peut pas dépasser le stock disponible actuel.',
            ]);
        }

        // Appliquer les modifications
        $stock->quantity = $validated['quantity'];
        $stock->updated_at = now(); // Mettre à jour explicitement le champ updated_at
        $stock->save(); // Sauvegarder les modifications

        return redirect()->route('stocks.index')->with('success', 'Stock mis à jour avec succès.');
    }
    public function edit(Stock $stock)
    {
        // Passer les données à la vue
        return view('stocks.edit', compact('stock'));
    }
    public function destroy(Stock $stock)
    {
        // Supprimer le stock
        $stock->delete();

        return redirect()->route('stocks.index')->with('success', 'Stock supprimé avec succès.');
    }


}
