<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class CheckoutController extends Controller
{
    public function show()
    {
        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.show')->with('error', 'Votre panier est vide');
        }

        return view('storefront.checkout', compact('cart'));
    }

    public function process(Request $request)
    {
        // Valider les données du formulaire
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            // Ajoutez d'autres champs selon vos besoins
        ]);

        // Si l'utilisateur n'est pas connecté, créez un compte ou demandez une connexion
        if (!Auth::check()) {
            // Option 1: Rediriger vers l'inscription avec les données du panier
            Session::put('checkout_data', $request->all());
            return redirect()->route('register')->with('info', 'Veuillez créer un compte pour finaliser votre commande');
            
            // Option 2: Créer un compte automatiquement
            // $user = \App\Models\User::create([...]);
            // Auth::login($user);
        }

        // Traitement de la commande
        $cart = Session::get('cart', []);
        $user = Auth::user();
        
        // Créer la commande
        $order = $user->orders()->create([
            'total' => $this->calculateTotal($cart),
            'status' => 'pending',
            'shipping_address' => $request->input('address'),
            'billing_address' => $request->input('address'),
            'phone' => $request->input('phone'),
            // Autres champs nécessaires
        ]);

        // Ajouter les produits de la commande
        foreach ($cart as $item) {
            $order->items()->create([
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ]);
        }

        // Vider le panier
        Session::forget('cart');

        // Rediriger vers la page de confirmation
        return redirect()->route('checkout.success', $order->id);
    }

    public function success($orderId)
    {
        $order = \App\Models\Order::findOrFail($orderId);
        return view('storefront.checkout-success', compact('order'));
    }

    protected function calculateTotal($cart)
    {
        return array_sum(array_map(function($item) {
            return $item['price'] * $item['quantity'];
        }, $cart));
    }
}