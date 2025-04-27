<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Transaction;
use App\Models\Stock;

class SalesController extends Controller
{
    // Méthode index : afficher les ventes
    public function index()
    {
        $sales = Sale::with('products')->paginate(10); // Charger les ventes avec les produits associés
        return view('sales.index', compact('sales'));
    }

    // Méthode store : gérer la création des ventes
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

        // Vérification du stock pour chaque produit
        foreach ($request->product_id as $index => $productId) {
            $product = Product::findOrFail($productId);
            if ($request->quantity[$index] > $product->stock->quantity) {
                return back()->withErrors(["quantity" => "Stock insuffisant pour le produit : {$product->name}"]);
            }
        }

        // Gestion des différents modes de paiement
        switch ($request->payment_mode) {
            case 'stripe':
                return $this->processStripePayment($request);
            case 'paypal':
                return $this->processPaypalPayment($request);
            default: // Espèces ou virement bancaire
                return $this->processDefaultPayment($request);
        }
    }

    // Méthode pour le traitement via Stripe
    private function processStripePayment(Request $request)
    {
        $sale = $this->createSale($request, false);

        // Enregistrement des transactions pour Stripe
        $this->recordTransactions($request, $sale);

        return view('sales.stripe_payment', [
            'sale' => $sale,
            'amount' => $request->total_amount,
            'stripe_key' => env('STRIPE_KEY'),
        ]);
    }

    // Méthode pour le traitement via PayPal
    private function processPaypalPayment(Request $request)
    {
        $sale = $this->createSale($request, false);

        // Enregistrement des transactions pour PayPal
        $this->recordTransactions($request, $sale);

        return view('sales.paypal_payment', [
            'sale' => $sale,
            'amount' => $request->total_amount,
            'client_id' => env('PAYPAL_CLIENT_ID'),
        ]);
    }

    // Méthode pour les paiements par défaut (espèces ou virement bancaire)
    private function processDefaultPayment(Request $request)
    {
        $sale = $this->createSale($request);

        // Enregistrement des transactions pour les paiements par défaut
        $this->recordTransactions($request, $sale);

        return redirect()->route('sales.show', $sale->id)->with('success', 'Vente enregistrée!');
    }

    // Méthode pour créer une vente
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

            // Ajouter les produits à la vente
            $sale->products()->attach($productId, [
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
            ]);

            // Réduire le stock
            if ($complete) {
                $product->stock->quantity -= $quantity;
                $product->stock->save();
            }
        }

        return $sale;
    }

    // Méthode pour enregistrer les transactions
    private function recordTransactions(Request $request, $sale)
    {
        foreach ($request->product_id as $index => $productId) {
            $product = Product::findOrFail($productId);
            $quantity = $request->quantity[$index];
            $unitPrice = $product->price;

            // Créer une transaction de type "exit"
            Transaction::create([
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $unitPrice,
                'type' => 'exit',
            ]);
        }
    }

    // Méthode pour afficher les détails d'une vente
    public function show($id)
    {
        $sale = Sale::with('products')->findOrFail($id);
        return view('sales.show', compact('sale'));
    }
}
