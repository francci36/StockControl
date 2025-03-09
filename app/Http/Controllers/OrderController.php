<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'supplier', 'items.product'])->get();
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
            'status' => 'required|string',
            'date' => 'required|date',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Création de la commande
        $order = Order::create([
            'user_id' => $validatedData['user_id'],
            'supplier_id' => $validatedData['supplier_id'],
            'status' => $validatedData['status'],
            'date' => $validatedData['date'],
        ]);

        // Ajout des éléments de commande
        foreach ($validatedData['items'] as $itemData) {
            $order->items()->create($itemData);
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
        'new_items' => 'array',
        'new_items.*.product_id' => 'exists:products,id',
        'new_items.*.quantity' => 'integer|min:1',
    ]);

    $order = Order::findOrFail($id);
    $order->update([
        'supplier_id' => $validated['supplier_id'],
        'date' => $validated['date'],
        'status' => $validated['status'],
    ]);

    // Mettre à jour les éléments existants
    foreach ($validated['items'] as $itemId => $itemData) {
        $order->items()->where('id', $itemId)->update($itemData);
    }

    // Ajouter les nouveaux éléments de commande
    if (isset($validated['new_items'])) {
        foreach ($validated['new_items'] as $newItemData) {
            $order->items()->create($newItemData);
        }
    }

    // Mettre à jour le stock seulement si la commande est arrivée
    if ($order->status === 'arrivé') {
        $order->updateStock();
    }

    return redirect()->route('orders.index')->with('success', 'Commande mise à jour avec succès');
}


    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('orders.index')->with('success', 'Commande supprimée avec succès');
    }

    public function updateStatus(Request $request, $id)
{
    $order = Order::with('items.product')->findOrFail($id);
    $previousStatus = $order->status;
    $newStatus = $request->input('status');

    Log::info('Mise à jour du statut de la commande.', [
        'order_id' => $order->id,
        'previous_status' => $previousStatus,
        'new_status' => $newStatus
    ]);

    // Mettez à jour le statut de la commande
    $order->update(['status' => $newStatus]);

    // Mettez à jour le stock des produits associés
    if ($newStatus === 'arrivé' && $previousStatus !== 'arrivé') {
        Log::info('Condition "arrivé" remplie. Appel à updateStock avec increment.');
        $order->updateStock(true);
    } elseif ($previousStatus === 'arrivé' && $newStatus !== 'arrivé') {
        Log::info('Condition "non arrivé" remplie. Appel à updateStock avec decrement.');
        $order->updateStock(false);
    }

    return redirect()->route('orders.index')->with('success', 'Statut de la commande mis à jour avec succès');
}


}