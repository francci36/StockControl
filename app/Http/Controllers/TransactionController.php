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
        $transactions = Transaction::with('product')->get();
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
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:0',
            'type' => 'required|in:entry,exit',
        ]);

        // Création de la transaction
        $transaction = Transaction::create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'type' => $request->type,
        ]);

        // Mise à jour du stock
        $product = Product::findOrFail($request->product_id);
        $stock = Stock::firstOrCreate(['product_id' => $product->id]);

        if ($request->type === 'entry') {
            // Ajouter la quantité au stock
            $stock->quantity += $request->quantity;
        } else {
            // Soustraire la quantité du stock
            $stock->quantity -= $request->quantity;
        }

        // Sauvegarder le stock mis à jour
        $stock->save();

        return redirect()->route('transactions.index')->with('success', 'Transaction enregistrée avec succès.');
    }
}


