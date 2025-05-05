<?php
// app/Http/Controllers/SalesController.php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\Transaction;

use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index()
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'manager', 'user'])) {
            abort(403, 'Accès interdit.');
        }

        $sales = Sale::with(['products', 'transactions'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('sales.index', compact('sales'));
    }

    public function store(Request $request)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'manager', 'user'])) {
            abort(403, 'Accès interdit.');
        }

        $validated = $request->validate([
            'product_id.*' => 'required|exists:products,id',
            'quantity.*' => 'required|integer|min:1',
            'price.*' => 'required|numeric|min:0',
            'payment_mode' => 'required|in:cash,credit_card,paypal,stripe,bank_transfer',
            'total_amount' => 'required|numeric|min:0',
        ]);

        // Vérification du stock
        foreach ($request->product_id as $index => $productId) {
            $product = Product::findOrFail($productId);
            if ($request->quantity[$index] > $product->stock->quantity) {
                return back()->withErrors(["quantity" => "Stock insuffisant pour {$product->name}"]);
            }
        }

        switch ($request->payment_mode) {
            case 'stripe':
                return $this->processStripePayment($request);
            case 'paypal':
                return $this->processPaypalPayment($request);
            default:
                return $this->processDefaultPayment($request);
        }
    }

    private function processStripePayment(Request $request)
    {
        $sale = $this->createSale($request, false);
        $this->recordTransactions($request, $sale);

        return view('sales.stripe_payment', [
            'sale' => $sale,
            'amount' => $request->total_amount,
            'stripe_key' => env('STRIPE_KEY'),
        ]);
    }

    private function processPaypalPayment(Request $request)
    {
        $sale = $this->createSale($request, false);
        $this->recordTransactions($request, $sale);

        return view('sales.paypal_payment', [
            'sale' => $sale,
            'amount' => $request->total_amount,
            'client_id' => env('PAYPAL_CLIENT_ID'),
        ]);
    }

    private function processDefaultPayment(Request $request)
    {
        $sale = $this->createSale($request);
        $this->recordTransactions($request, $sale);

        return redirect()->route('sales.show', $sale->id)
            ->with('success', 'Vente enregistrée avec succès !');
    }

    private function createSale(Request $request, $complete = true)
    {
        $sale = Sale::create([
            'payment_mode' => $request->payment_mode,
            'status' => $complete ? 'completed' : 'pending',
            'user_id' => auth()->id(),
            'total_amount' => $request->total_amount,
        ]);

        foreach ($request->product_id as $index => $productId) {
            $product = Product::findOrFail($productId);
            $quantity = $request->quantity[$index];
            $unitPrice = $product->price;

            $sale->products()->attach($productId, [
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_price' => $quantity * $unitPrice,
            ]);

            if ($complete) {
                $product->stock->decrement('quantity', $quantity);
            }
        }

        return $sale;
    }

    public function recordTransactions(Request $request, Sale $sale, $isReturn = false)
    {
        foreach ($request->product_id as $index => $productId) {
            $product = Product::findOrFail($productId);
            $quantity = $request->quantity[$index];
            $unitPrice = $product->price;

            Transaction::create([
                'sale_id' => $sale->id,
                'product_id' => $productId,
                'quantity' => $isReturn ? $quantity : $quantity, // Quantité positive dans les deux cas
                'price' => $unitPrice,
                'type' => $isReturn ? 'entry' : 'exit', // Type différent selon si c'est un retour
                'reason' => $isReturn ? ($request->reason ?? 'Retour client') : 'Vente client',
            ]);
        }
    }

    public function show(Sale $sale)
    {
        $sale->load(['products', 'transactions']);
        return view('sales.show', compact('sale'));
    }

    public function cancel(Sale $sale)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'manager'])) {
            abort(403, 'Accès interdit.');
        }

        DB::beginTransaction();

        try {
            $sale->status = 'canceled';
            $sale->save();

            // Création d'une transaction de retour pour chaque produit
            foreach ($sale->products as $product) {
                $quantity = $product->pivot->quantity;
                
                // Mise à jour du stock
                $product->stock->increment('quantity', $quantity);

                // Enregistrement de la transaction de retour
                Transaction::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->pivot->unit_price,
                    'type' => 'entry',
                    'reason' => 'Annulation de vente',
                ]);
            }

            DB::commit();

            return redirect()->route('sales.index')
                ->with('success', 'Vente annulée et stock mis à jour !');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erreur lors de l\'annulation : ' . $e->getMessage()]);
        }
    }

    public function destroy(Sale $sale)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'manager'])) {
            abort(403, 'Accès interdit.');
        }

        $sale->delete();

        return redirect()->route('sales.index')
            ->with('success', 'Vente supprimée avec succès !');
    }
}