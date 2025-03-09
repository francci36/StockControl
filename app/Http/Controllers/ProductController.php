<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Affiche le formulaire de création de produit pour un fournisseur spécifique.
     *
     * @param int $supplier_id
     * @return \Illuminate\View\View
     */
    public function create($supplier_id)
    {
        // Récupère le fournisseur ou renvoie une erreur 404 si non trouvé
        $supplier = Supplier::findOrFail($supplier_id);

        // Retourne la vue avec le fournisseur
        return view('products.create', compact('supplier'));
    }

    /**
     * Enregistre un nouveau produit dans la base de données.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'supplier_id' => 'required|exists:suppliers,id',
        ]);

        Product::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'supplier_id' => $validated['supplier_id'],
            'quantity' => 0, // Quantité initiale à zéro
            'stock' => 0, // Stock initial à zéro
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Produit ajouté au fournisseur avec succès');
    }

    /**
     * Affiche la liste des produits.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $products = Product::with(['supplier', 'stock'])->get(); // Charger les relations `supplier` et `stock`
        return view('products.index', compact('products'));
    }
}
