<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('stocks.index', compact('products'));
    }
    public function update(Request $request, Stock $stock)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:0'
        ]);

        $stock->update($validated);
        return redirect()->route('stocks.index')->with('success', 'Stock mis à jour');
    }
    

}