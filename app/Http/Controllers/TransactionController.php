<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\Sale;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\CartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


use Illuminate\Support\Facades\Session;

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
        // Vue normale pour créer une transaction
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'manager', 'user'])) {
            abort(403, 'Accès interdit.');
        }

        $products = Product::with('stock')->get();
        $sales = Sale::where('status', 'completed')->orderBy('created_at', 'desc')->get();

        return view('transactions.create', [
            'products' => $products,
            'sales' => $sales,
            'from_cart' => false
        ]);
    }

    public function createFromCart()
    {
        // Vue spéciale pour le checkout depuis le panier
        if (!Session::has('checkout_data')) {
            return redirect()->route('transactions.create');
        }

        $products = Product::whereIn('id', array_keys(Session::get('checkout_data.cart_items')))
                        ->with('stock')
                        ->get();

        return view('transactions.create', [
            'products' => $products,
            'from_cart' => true,
            'checkout_data' => Session::get('checkout_data')
        ]);
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

            // Si c'était une commande depuis le panier
            if ($request->has('from_cart')) {
                // Vider le panier
                Session::forget('cart');
                // Nettoyer les données temporaires
                app(CartController::class)->clearCheckoutData();
                
                return redirect()->route('transactions.create')
                                ->with('success', 'Paiement effectué et panier vidé !');
            }
        }

        return redirect()->route('sales.index')->with('success', 'Vente enregistrée !');
    }

    public function storeReturn(Request $request)
    {
        $validated = $request->validate([
            'sale_id' => 'required|exists:sales,id',
            'product_id.*' => 'required|exists:products,id',
            'quantity.*' => 'required|integer|min:0', // Permettre 0 pour les produits non retournés
            'price.*' => 'required|numeric|min:0',
            'reason' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $sale = Sale::findOrFail($validated['sale_id']);
            $returnedProducts = [];

            foreach ($validated['product_id'] as $index => $productId) {
                $quantity = $validated['quantity'][$index];
                
                // Ignorer les produits avec quantité 0
                if ($quantity <= 0) {
                    continue;
                }

                $product = Product::findOrFail($productId);
                $unitPrice = $validated['price'][$index];
                
                // Vérifier que la quantité retournée ne dépasse pas la quantité vendue
                $soldQuantity = $sale->products()->where('product_id', $productId)->first()->pivot->quantity;
                if ($quantity > $soldQuantity) {
                    throw new \Exception("La quantité retournée pour {$product->name} dépasse la quantité vendue.");
                }

                // Ajouter au tableau des produits retournés
                $returnedProducts[$productId] = [
                    'quantity' => -$quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => -($quantity * $unitPrice),
                ];

                // Mise à jour du stock
                $product->stock->increment('quantity', $quantity);

                // Enregistrement de la transaction de retour
                Transaction::create([
                    'sale_id' => $sale->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $unitPrice,
                    'type' => 'entry',
                    'reason' => $validated['reason'],
                ]);
            }

            // Mettre à jour la vente avec les produits retournés
            foreach ($returnedProducts as $productId => $details) {
                $sale->products()->syncWithoutDetaching([
                    $productId => $details
                ]);
            }

            // Mettre à jour le montant total de la vente
            $sale->update([
                'total_amount' => $sale->total_amount - array_sum(array_column($returnedProducts, 'total_price'))
            ]);

            DB::commit();

            return redirect()->route('sales.index')->with('success', 'Retour enregistré avec succès !');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erreur : ' . $e->getMessage()]);
        }
    }
    



    
}

