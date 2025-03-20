<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Supplier;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Affiche la liste des commandes.
     */
    public function index()
    {
        $orders = Order::with(['user', 'supplier', 'products'])->paginate(20); // Pagination
        return view('orders.index', compact('orders'));
    }

    /**
     * Affiche le formulaire de création d'une commande pour un fournisseur donné.
     */
    public function create($supplier_id)
    {
        $users = User::all();
        $suppliers = Supplier::all();

        // Récupérer uniquement les produits du fournisseur sélectionné
        $products = Product::where('supplier_id', $supplier_id)->get();

         // ID de l'utilisateur connecté
        $user_id = auth()->id();

        return view('orders.create', compact('users', 'suppliers', 'products', 'supplier_id', 'user_id'));
    }

    /**
     * Enregistre une nouvelle commande dans la base de données.
     */
    public function store(Request $request)
    {
        // Validation des données
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'date' => 'required|date',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Création de la commande
        $order = Order::create([
            'user_id' => $validatedData['user_id'],
            'supplier_id' => $validatedData['supplier_id'],
            'status' => 'pending',
            'date' => $validatedData['date'],
        ]);

        // Ajout des produits à la commande
        foreach ($validatedData['items'] as $itemData) {
            $order->products()->attach($itemData['product_id'], [
                'quantity' => $itemData['quantity'],
            ]);
        }

        return redirect()->route('orders.index')->with('success', 'Commande créée avec succès.');
    }

    /**
     * Affiche le formulaire d'édition d'une commande existante.
     */
    public function edit($id)
    {
        $order = Order::findOrFail($id);

        // Produits disponibles pour le fournisseur associé à la commande
        $products = Product::where('supplier_id', $order->supplier_id)->get();
        $suppliers = Supplier::all();

        return view('orders.edit', compact('order', 'products', 'suppliers'));
    }

    /**
     * Met à jour une commande existante.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'date' => 'required|date',
            'status' => 'required|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'supplier_id' => $validated['supplier_id'],
            'date' => $validated['date'],
            'status' => $validated['status'],
        ]);

        // Mise à jour des produits associés
        $order->products()->detach(); // Supprimer les anciens
        foreach ($validated['items'] as $itemData) {
            $order->products()->attach($itemData['product_id'], [
                'quantity' => $itemData['quantity'],
            ]);
        }

        return redirect()->route('orders.index')->with('success', 'Commande mise à jour avec succès.');
    }

    /**
     * Supprime une commande.
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->products()->detach(); // Supprime les relations pivot
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Commande supprimée avec succès.');
    }

    /**
     * Met à jour le statut d'une commande et ajuste les stocks si nécessaire.
     */
    public function updateStatus(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->status = $request->input('status');
        $order->save();

        if ($order->status === 'arrivé') {
            $this->updateStock($order, true);
        }

        return redirect()->route('orders.index')->with('success', 'Statut de la commande mis à jour.');
    }

    /**
     * Met à jour les stocks pour les produits d'une commande.
     */
    private function updateStock(Order $order, $increment = true)
    {
        foreach ($order->products as $product) {
            $quantity = $product->pivot->quantity;

            $stock = Stock::firstOrCreate(
                ['product_id' => $product->id],
                ['price' => $product->price, 'quantity' => 0]
            );

            $stock->quantity += ($increment ? $quantity : -$quantity);
            $stock->save();

            Log::info('Stock mis à jour', [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'increment' => $increment,
            ]);
        }
    }

    /**
     * Récupère les produits d'un fournisseur.
     */
    public function getProductsBySupplier($supplierId)
    {
        $products = Product::where('supplier_id', $supplierId)->get();
        return response()->json($products);
    }

    /**
     * Affiche les détails d'une commande.
     */
    public function show(Order $order)
    {
        // Chargement des relations
        $order->load('user', 'supplier', 'products');
        
        return view('orders.show', compact('order'));
    }
}