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
        $transactions = Transaction::with('product')
            ->where('type', 'exit')
            ->orderBy('created_at', 'desc')
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
    $validated = $request->validate([
        'product_id.*' => 'required|exists:products,id',
        'quantity.*' => 'required|integer|min:1',
        'price.*' => 'required|numeric|min:0',
        'payment_mode' => 'required|in:cash,credit_card,paypal,stripe,bank_transfer',
        'total_amount' => 'required|numeric|min:0',
    ]);

    // Vérification du stock et création de la vente
    foreach ($request->product_id as $index => $productId) {
        $product = Product::findOrFail($productId);
        if ($request->quantity[$index] > $product->stock->quantity) {
            return back()->withErrors(['quantity' => "Stock insuffisant pour {$product->name}"]);
        }
    }

    // Créer la vente
    $sale = Sale::create([
        'payment_mode' => $request->payment_mode,
        'total_amount' => $request->total_amount,
        'status' => 'completed',
        'user_id' => auth()->id(),
    ]);

    // Ajouter les produits liés à la table pivot et enregistrer les transactions
    foreach ($request->product_id as $index => $productId) {
        $product = Product::findOrFail($productId);
        $quantity = $request->quantity[$index];
        $unitPrice = $product->price;
        $totalPrice = $quantity * $unitPrice;

        // Enregistrer la vente dans la table pivot
        $sale->products()->attach($productId, [
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_price' => $totalPrice,
        ]);

        // Réduire le stock
        $product->stock->quantity -= $quantity;
        $product->stock->save();

        // Enregistrer la transaction de type "exit"
        Transaction::create([
            'product_id' => $productId,
            'quantity' => $quantity,
            'price' => $unitPrice,
            'type' => 'exit',
        ]);
    }

    return redirect()->route('sales.index')->with('success', 'Vente créée et enregistrée comme une transaction !');
}


}
