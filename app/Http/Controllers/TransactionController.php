<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\Stock;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        // Récupérer uniquement les transactions "exit"
        $transactions = Transaction::with('product')
            ->orderBy('created_at', 'desc') // Tri pour afficher les plus récentes
            ->paginate(10);

        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        // Charger les produits avec leurs stocks pour les ventes
        $products = Product::with('stock')->get();

        return view('transactions.create', compact('products'));
    }

    public function store(Request $request)
    {
        // Validation des données
        $validatedData = $request->validate([
            'product_id' => 'required|array',
            'product_id.*' => 'exists:products,id',
            'quantity' => 'required|array',
            'quantity.*' => 'numeric|min:1',
            'price' => 'required|array',
            'price.*' => 'numeric|min:0',
        ]);

        foreach ($request->product_id as $index => $productId) {
            $product = Product::findOrFail($productId);
            $stock = Stock::firstOrCreate(['product_id' => $productId]);

            // Vérification du stock avant soustraction
            if ($stock->quantity < $request->quantity[$index]) {
                return back()->withErrors([
                    'quantity' => "La quantité demandée pour le produit {$product->name} dépasse le stock disponible ({$stock->quantity})."
                ]);
            }

            // Réduire le stock
            $stock->quantity -= $request->quantity[$index];

            // Enregistrer la transaction de type "exit"
            Transaction::create([
                'product_id' => $productId,
                'quantity' => $request->quantity[$index],
                'price' => $request->price[$index],
                'type' => 'exit', // Type fixé à "exit"
            ]);

            // Sauvegarder le stock mis à jour
            $stock->save();
        }

        return redirect()->route('transactions.index')->with('success', 'Transactions enregistrées avec succès.');
    }
}
