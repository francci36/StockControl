<?php

// app/Http/Controllers/TransactionController.php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
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
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'type' => 'required|in:entry,exit',
        ]);

        // Utiliser une transaction de base de données
        DB::beginTransaction();
        try {
            // Création de la transaction
            $transaction = Transaction::create($request->all());

            // Mettre à jour le stock
            if ($transaction->type == 'entry') {
                $transaction->product->increment('stock', $transaction->quantity);
            } else {
                // Vérifier si le stock est suffisant avant de décrémenter
                if ($transaction->product->stock < $transaction->quantity) {
                    return redirect()->back()->with('error', 'Stock insuffisant pour cette transaction.');
                }
                $transaction->product->decrement('stock', $transaction->quantity);

                // Vérifier si le stock est inférieur au seuil et envoyer une notification
                if ($transaction->product->stock < $transaction->product->stock_threshold) {
                    $transaction->product->notify(new LowStockNotification($transaction->product));
                }
            }

            // Valider la transaction
            DB::commit();

            return redirect()->route('transactions.index')->with('success', 'Transaction enregistrée avec succès.');
        } catch (\Exception $e) {
            // Annuler la transaction en cas d'erreur
            DB::rollBack();
            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'enregistrement de la transaction.');
        }
    }
}


