<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\Sale;
use App\Http\Controllers\SalesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'manager', 'user'])) {
            abort(403, 'Accès interdit.');
        }

        // Vérification du type de transaction (exit ou entry)
        $type = $request->query('type', 'exit'); // ✅ Par défaut, affiche les ventes si aucun type n'est spécifié

        // Récupération des transactions avec filtres dynamiques
        $query = Transaction::with('product')->orderBy('created_at', 'desc');

        if ($request->filled('type')) {
            $query->where('type', $type); // ✅ Filtrage dynamique avec gestion du type
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        $transactions = $query->paginate(10)->appends($request->query());

        $products = Product::orderBy('name')->get();

        // Debug temporaire pour vérifier la requête SQL générée 🔍
        

        return view('transactions.index', compact('transactions', 'products'));
    }


    public function create()
    {
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'manager', 'user'])) {
            abort(403, 'Accès interdit.');
        }

        $products = Product::with('stock')->get();
        $sales = Sale::where('status', 'completed')->orderBy('created_at', 'desc')->get();

        return view('transactions.create', compact('products', 'sales'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id.*' => 'required|exists:products,id',
            'quantity.*' => 'required|integer|min:1',
            'price.*' => 'required|numeric|min:0',
            'payment_mode' => 'required|in:cash,credit_card,paypal,stripe,bank_transfer',
            'total_amount' => 'required|numeric|min:0',
        ]);

        foreach ($request->product_id as $index => $productId) {
            $product = Product::findOrFail($productId);
            if ($request->quantity[$index] > $product->stock->quantity) {
                return back()->withErrors(['quantity' => "Stock insuffisant pour {$product->name}"]);
            }
        }

        $sale = Sale::create([
            'payment_mode' => $request->payment_mode,
            'total_price' => $request->total_amount,
            'total_amount' => $request->total_amount,
            'status' => 'completed',
            'user_id' => auth()->id(),
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

            $product->stock->decrement('quantity', $quantity);

            Transaction::create([
                'product_id' => $productId,
                'quantity' => abs($quantity), // ✅ Toujours positif pour les ventes
                'price' => $unitPrice,
                'type' => 'exit',
                'reason' => $request->input('reason')[$index] ?? 'Vente client',
            ]);
        }

        return redirect()->route('sales.index')->with('success', 'Vente enregistrée !');
    }

    public function storeReturn(Request $request)
{
    



    // Validation des données reçues
    $validated = $request->validate([
        'sale_id' => 'required|exists:sales,id',
        'product_id.*' => 'required|exists:products,id',
        'quantity.*' => 'required|integer|min:1',
        'price.*' => 'required|numeric|min:0',
        'reason' => 'required|string|max:255',
    ]);

    DB::beginTransaction();

    try {
        $sale = Sale::findOrFail($validated['sale_id']);

        foreach ($validated['product_id'] as $index => $productId) {
            $product = Product::findOrFail($productId);
            $quantity = abs($validated['quantity'][$index]);
            $unitPrice = $validated['price'][$index];

            // Enregistrement du retour sans supprimer d'anciennes données
            $sale->products()->syncWithoutDetaching([
                $productId => [
                    'quantity' => -$quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $quantity * $unitPrice,
                ]
            ]);

            // Mise à jour du stock du produit
            if ($product->stock) {
                $product->stock->increment('quantity', $quantity);
            } else {
                $product->update(['stock' => $product->stock + $quantity]);
            }

            // Enregistrement de la transaction de retour
            $transaction = Transaction::create([
                'sale_id' => $sale->id,
                'product_id' => $productId,
                'quantity' => $quantity, // ✅ Correction ici
                'price' => $unitPrice,
                'type' => 'entry',
                'reason' => $validated['reason'],
            ]);

            if (!$transaction) {
                throw new \Exception("La transaction de retour n'a pas pu être enregistrée.");
            }
        }

        // Appel correct à `recordTransactions()` depuis `SalesController`
        $salesController = app(SalesController::class);
        $salesController->recordTransactions($request, $sale, true);

        DB::commit();

        return redirect()->route('sales.index')->with('success', 'Retour enregistré avec succès !');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => 'Erreur : ' . $e->getMessage()]);
    }
}



    
}

