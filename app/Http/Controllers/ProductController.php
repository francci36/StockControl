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
        // Récupérer le fournisseur ou renvoyer une erreur 404 si non trouvé
        $supplier = Supplier::findOrFail($supplier_id);

        // Retourner la vue avec le fournisseur
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
        // Validation des données de la requête
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0', // S'assurer que le prix est positif
            'supplier_id' => 'required|exists:suppliers,id',
            'stock_threshold' => 'nullable|integer|min:0', // Validation du seuil de stock
        ]);

        // Création du produit avec gestion des valeurs par défaut
        Product::create([
            'name' => $validated['name'], // Champ requis
            'description' => $validated['description'] ?? '', // Valeur par défaut si non fournie
            'price' => $validated['price'],
            'supplier_id' => $validated['supplier_id'],
            'quantity' => 0, // Quantité initiale par défaut
            'stock_threshold' => $validated['stock_threshold'] ?? 0, // Valeur par défaut
        ]);

        // Rediriger vers l'index des fournisseurs avec un message de succès
        return redirect()->route('suppliers.index')->with('success', 'Produit ajouté au fournisseur avec succès.');
    }

    /**
     * Affiche la liste des produits.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Récupérer tous les produits avec leur fournisseur associé
        $products = Product::with('supplier')->get();

        // Retourner la vue avec les produits
        return view('products.index', compact('products'));
    }
}
