<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Transaction;
use App\Models\Stock;

class SalesController extends Controller
{
    // Afficher la liste des ventes
    public function index()
    {
        // Récupérer les ventes avec pagination
        $sales = Sale::with('product')->paginate(10);
        return view('sales.index', compact('sales'));
    }

    // Afficher le formulaire de création d'une nouvelle vente
    public function create()
    {
        $products = Product::with('stock')->get(); // Inclure les stocks pour valider la quantité
        return view('sales.create', compact('products'));
    }

    // Enregistrer une nouvelle vente
    public function store(Request $request)
    {
        // Validation des données
        $validated = $request->validate([
            'product_id.*' => 'required|exists:products,id',
            'quantity.*' => 'required|integer|min:1',
            'price.*' => 'required|numeric|min:0',
            'payment_mode' => 'required|in:cash,credit_card,paypal,bank_transfer',
        ]);

        foreach ($request->product_id as $index => $productId) {
            $product = Product::findOrFail($productId);
            $quantity = $request->quantity[$index];

            // Vérification du stock disponible
            if ($quantity > $product->stock->quantity) {
                return back()->withErrors(['quantity' => "La quantité demandée pour le produit {$product->name} dépasse le stock disponible ({$product->stock->quantity})."]);
            }

            // Réduire le stock
            $product->stock->quantity -= $quantity;
            $product->stock->save();

            // Enregistrer la vente
            $sale = Sale::create([
                'product_id' => $productId,
                'quantity' => $quantity,
                'total_price' => $quantity * $product->price, // Calculer le prix total
                'payment_mode' => $request->payment_mode,
            ]);

            // Enregistrer une transaction correspondante
            Transaction::create([
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->price,
                'type' => 'exit', // Toutes les ventes sont de type "exit"
            ]);
        }

        return redirect()->route('sales.index')->with('success', 'Vente enregistrée avec succès.');
    }

    // Afficher une vente spécifique
    public function show($id)
    {
        $sale = Sale::with('product')->findOrFail($id);
        return view('sales.show', compact('sale'));
    }

    // Afficher le formulaire d'édition d'une vente
    public function edit($id)
    {
        $sale = Sale::with('product')->findOrFail($id);
        $products = Product::all();
        return view('sales.edit', compact('sale', 'products'));
    }

    // Mettre à jour une vente existante
    public function update(Request $request, $id)
    {
        $sale = Sale::findOrFail($id);

        // Validation des données
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'payment_mode' => 'required|in:cash,credit_card,paypal,bank_transfer',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Vérification et mise à jour du stock
        if ($request->quantity > $product->stock->quantity) {
            return back()->withErrors(['quantity' => "La quantité demandée dépasse le stock disponible ({$product->stock->quantity})."]);
        }

        // Mettre à jour la vente
        $sale->update([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'total_price' => $request->quantity * $product->price,
            'payment_mode' => $request->payment_mode,
        ]);

        // Mettre à jour le stock
        $product->stock->quantity -= $request->quantity - $sale->quantity;
        $product->stock->save();

        return redirect()->route('sales.index')->with('success', 'Vente mise à jour avec succès.');
    }

    // Supprimer une vente
    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);

        // Réapprovisionner le stock
        $product = $sale->product;
        $product->stock->quantity += $sale->quantity;
        $product->stock->save();

        $sale->delete();

        return redirect()->route('sales.index')->with('success', 'Vente supprimée avec succès.');
    }
}
