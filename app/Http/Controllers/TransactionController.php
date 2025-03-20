<?php

// app/Http/Controllers/TransactionController.php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\Stock;
use App\Notifications\LowStockNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('product')->paginate(10);
        return view('transactions.index', compact('transactions'));
    }

    public function create()
    {
        $products = Product::all();
        return view('transactions.create', compact('products'));
    }

    public function store(Request $request)
{
    // Validation des données
    $request->validate([
        'product_id' => 'required|array',
        'product_id.*' => 'exists:products,id',
        'quantity' => 'required|array',
        'quantity.*' => 'numeric|min:1',
        'price' => 'required|array',
        'price.*' => 'numeric|min:0',
        'type' => 'required|in:entry,exit',
    ]);

    // Boucle pour traiter chaque produit
    foreach ($request->product_id as $index => $productId) {
        // Création de la transaction
        $transaction = Transaction::create([
            'product_id' => $productId,
            'quantity' => $request->quantity[$index],
            'price' => $request->price[$index],
            'type' => $request->type,
        ]);

        // Mise à jour du stock
        $product = Product::findOrFail($productId);
        $stock = Stock::firstOrCreate(['product_id' => $product->id]);

        if ($request->type === 'entry') {
            // Ajouter la quantité au stock
            $stock->quantity += $request->quantity[$index];
        } else {
            // Soustraire la quantité du stock
            $stock->quantity -= $request->quantity[$index];
        }

        // Sauvegarder le stock mis à jour
        $stock->save();
    }

    // Redirection avec un message de succès
    return redirect()->route('transactions.index')->with('success', 'Transactions enregistrées avec succès.');
}

}


