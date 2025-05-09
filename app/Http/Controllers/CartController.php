<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Category; 
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $cart = Session::get('cart', []);
        $categories = Category::all(); 
        return view('storefront.cart', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            // Vous devrez adapter cette partie selon votre modèle Product
            $product = \App\Models\Product::findOrFail($productId);
            
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'quantity' => $quantity
            ];
        }

        Session::put('cart', $cart);

        return response()->json([
            'success' => true,
            'cart_count' => $this->getCartCount()
        ]);
    }

    public function checkout()
    {
        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide');
        }

        // Préparer les données pour la vue de transaction
        $cart_items = [];
        $total = 0;
        
        foreach ($cart as $id => $item) {
            $product = Product::find($id);
            if ($product) {
                $cart_items[] = [
                    'id' => $id,
                    'name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'stock' => $product->stock->quantity ?? 0
                ];
                $total += $item['price'] * $item['quantity'];
            }
        }

        return redirect()->route('transactions.create')->with([
            'from_cart' => true,
            'cart_items' => $cart_items,
            'cart_total' => $total
        ]);
    }
    

    protected function clearCheckoutData()
    {
        Session::forget('checkout_data');
    }

    protected function calculateTotal($cart)
    {
        return array_sum(array_map(function($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $productId = $request->input('product_id');
        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put('cart', $cart);
        }

        return response()->json([
            'success' => true,
            'cart_count' => $this->getCartCount()
        ]);
    }

    
    public function clear()
    {
        Session::forget('cart');
        return response()->json(['success' => true]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        $cart = Session::get('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            Session::put('cart', $cart);
        }

        return response()->json([
            'success' => true,
            'cart_count' => $this->getCartCount()
        ]);
    }

    protected function getCartCount()
    {
        $cart = Session::get('cart', []);
        return array_sum(array_column($cart, 'quantity'));
    }

    
}
