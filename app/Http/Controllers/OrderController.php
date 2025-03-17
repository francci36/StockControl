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
    public function index()
    {
        // Chargement des relations nécessaires
        $orders = Order::with(['user', 'supplier', 'products'])->get();
        return view('orders.index', compact('orders'));
    }

    public function create($supplier_id)
    {
        $users = User::all();
        $suppliers = Supplier::all();
        $products = Product::all();
        return view('orders.create', compact('users', 'suppliers', 'products', 'supplier_id'));
    }

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
            'status' => 'pending', // Statut par défaut
            'date' => $validatedData['date'],
        ]);

        // Ajout des éléments à la commande via la table pivot
        foreach ($validatedData['items'] as $itemData) {
            $order->products()->attach($itemData['product_id'], [
                'quantity' => $itemData['quantity'],
            ]);
        }

        return redirect()->route('orders.index')->with('success', 'Commande créée avec succès.');
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        $products = Product::all();
        $suppliers = Supplier::all();

        return view('orders.edit', compact('order', 'products', 'suppliers'));
    }

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

        // Mise à jour des éléments de commande
        $order->products()->detach(); // Supprime les anciens éléments
        foreach ($validated['items'] as $itemData) {
            $order->products()->attach($itemData['product_id'], [
                'quantity' => $itemData['quantity'],
            ]);
        }

        return redirect()->route('orders.index')->with('success', 'Commande mise à jour avec succès.');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->products()->detach(); // Supprime les relations avec les produits
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Commande supprimée avec succès.');
    }

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

    private function updateStock(Order $order, $increment = true)
    {
        foreach ($order->products as $product) {
            $quantity = $product->pivot->quantity;

            $stock = Stock::where('product_id', $product->id)->first();

            if ($stock) {
                $stock->quantity += ($increment ? $quantity : -$quantity);
                $stock->save();
            } else {
                Stock::create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                ]);
            }

            Log::info('Stock mis à jour', [
                'product_id' => $product->id,
                'quantity' => $quantity,
                'increment' => $increment,
            ]);
        }
    }
}
