<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Transaction;
use App\Models\Stock;

class SalesController extends Controller
{
    // Afficher les ventes
    public function index()
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'manager', 'user'])) {
            abort(403, 'Accès interdit.');
        }

        $sales = Sale::with('products')->paginate(10); // Chargement avec les produits associés
        return view('sales.index', compact('sales'));
    }

    // Gérer la création des ventes
    public function store(Request $request)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'manager', 'user'])) {
            abort(403, 'Accès interdit.');
        }

        // Validation des données
        $validated = $request->validate([
            'product_id.*' => 'required|exists:products,id',
            'quantity.*' => 'required|integer|min:1',
            'price.*' => 'required|numeric|min:0',
            'payment_mode' => 'required|in:cash,credit_card,paypal,stripe,bank_transfer',
            'total_amount' => 'required|numeric|min:0',
        ]);

        // Vérification du stock pour chaque produit
        foreach ($request->product_id as $index => $productId) {
            $product = Product::findOrFail($productId);
            if ($request->quantity[$index] > $product->stock->quantity) {
                return back()->withErrors(["quantity" => "Stock insuffisant pour le produit : {$product->name}"]);
            }
        }

        // Gestion des modes de paiement
        switch ($request->payment_mode) {
            case 'stripe':
                return $this->processStripePayment($request);
            case 'paypal':
                return $this->processPaypalPayment($request);
            default: 
                return $this->processDefaultPayment($request);
        }
    }

    // Traitement via Stripe
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

    // Traitement via PayPal
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

    // Paiement par espèces ou virement bancaire
    private function processDefaultPayment(Request $request)
    {
        $sale = $this->createSale($request);
        $this->recordTransactions($request, $sale);

        return redirect()->route('sales.show', $sale->id)->with('success', 'Vente enregistrée!');
    }

    // Création d'une vente
    private function createSale(Request $request, $complete = true)
    {
        $totalPrice = array_sum($request->total);

        $sale = Sale::create([
            'payment_mode' => $request->payment_mode,
            'status' => $complete ? 'completed' : 'pending',
            'user_id' => auth()->id(),
            'total_price' => $totalPrice,
            'total_amount' => $request->total_amount,
        ]);

        foreach ($request->product_id as $index => $productId) {
            $product = Product::findOrFail($productId);
            $quantity = $request->quantity[$index];
            $unitPrice = $product->price;
            $totalPrice = $quantity * $unitPrice;

            $sale->products()->attach($productId, [
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
            ]);

            if ($complete) {
                $product->stock->quantity -= $quantity;
                $product->stock->save();
            }
        }

        return $sale;
    }

    // Enregistrement des transactions
    private function recordTransactions(Request $request, $sale)
    {
        foreach ($request->product_id as $index => $productId) {
            $product = Product::findOrFail($productId);
            $quantity = $request->quantity[$index];
            $unitPrice = $product->price;

            Transaction::create([
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $unitPrice,
                'type' => 'exit',
                'reason' => 'Vente client', // Ajout de la raison automatique
            ]);
        }
    }

    // Affichage des détails d'une vente
    public function show($id)
    {
        $sale = Sale::with('products')->findOrFail($id);
        return view('sales.show', compact('sale'));
    }

    // Annulation d'une vente (réservé aux admins et managers)
    public function cancel($id)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'manager'])) {
            abort(403, 'Accès interdit.');
        }

        $sale = Sale::findOrFail($id);
        $sale->status = 'canceled';
        $sale->save();

        foreach ($sale->products as $product) {
            $product->stock->quantity += $product->pivot->quantity;
            $product->stock->save();
        }

        return redirect()->route('sales.index')->with('success', 'Vente annulée!');
    }

    // Suppression d'une vente (réservé aux admins et managers)
    public function destroy($id)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'manager'])) {
            abort(403, 'Accès interdit.');
        }

        $sale = Sale::findOrFail($id);
        $sale->delete();

        return redirect()->route('sales.index')->with('success', 'Vente supprimée!');
    }
}
