<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'manager', 'user'])) {
            abort(403, 'Accès interdit.');
        }

        $stocks = Stock::with('product')->paginate(20); // Pagination
        Log::info('Stocks récupérés : ', $stocks->items());

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
    public function edit($id)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'manager'])) {
            abort(403, 'Accès interdit.');
        }

        $stock = Stock::findOrFail($id);
        return view('stocks.edit', compact('stock'));
    }

    public function destroy($id)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'manager'])) {
            abort(403, 'Accès interdit.');
        }

        $stock = Stock::findOrFail($id);
        $stock->delete();

        return redirect()->route('stocks.index')->with('success', 'Produit supprimé.');
    }



}
