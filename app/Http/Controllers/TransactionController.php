<?php

// app/Http/Controllers/TransactionController.php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Notifications\LowStockNotification;
use Illuminate\Http\Request;

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
        $transaction = Transaction::create($request->all());

        if ($transaction->type == 'entry') {
            $transaction->product->increment('stock', $transaction->quantity);
        } else {
            $transaction->product->decrement('stock', $transaction->quantity);

            // Vérifier si le stock est inférieur au seuil et envoyer une notification
            if ($transaction->product->stock < $transaction->product->stock_threshold) {
                $transaction->product->notify(new LowStockNotification($transaction->product));
            }
        }

        return redirect()->route('transactions.index');
    }
}


