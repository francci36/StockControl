<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Product;
use Illuminate\Http\Request;


class StockController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'manager', 'user'])) {
            abort(403, 'Accès interdit.');
        }
    
        // Récupération de tous les noms de produits pour l'autocomplétion
        $allProductNames = Product::pluck('name')->toArray();
    
        // Si c'est une requête AJAX (recherche en temps réel)
        if ($request->ajax()) {
            $search = $request->input('search');
            
            $stocks = Stock::with('product')
                ->when($search, function($query) use ($search) {
                    $query->whereHas('product', function($q) use ($search) {
                        $q->where('name', 'like', '%'.$search.'%');
                    });
                })
                ->paginate(20);
    
            return response()->json([
                'html' => view('stocks.partials.table', compact('stocks'))->render(),
                'pagination' => $stocks->links()->toHtml()
            ]);
        }
    
        // Requête normale (premier chargement)
        $stocks = Stock::with('product')->paginate(20);
    
        return view('stocks.index', compact('stocks', 'allProductNames'));
    }

    public function update(Request $request, Stock $stock)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        if ($validated['quantity'] > $stock->quantity) {
            return redirect()->route('stocks.index')->withErrors([
                'quantity' => 'La quantité mise à jour ne peut pas dépasser le stock disponible actuel.',
            ]);
        }

        $stock->quantity = $validated['quantity'];
        $stock->updated_at = now();
        $stock->save();

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