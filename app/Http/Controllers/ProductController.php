<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        // Vérifier si le fournisseur existe ou renvoyer une erreur 404
        $supplier = Supplier::findOrFail($supplier_id);

        // Retourner la vue pour créer un produit associé au fournisseur
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
        // Valider les données
        $validatedData = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'products' => 'required|array',
            'products.*.name' => 'required|string|max:255',
            'products.*.description' => 'nullable|string',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.stock_threshold' => 'nullable|integer|min:0',
        ]);

        try {
            // Parcourir chaque produit et l'enregistrer
            foreach ($validatedData['products'] as $productData) {
                Product::create([
                    'name' => $productData['name'],
                    'description' => $productData['description'] ?? null,
                    'price' => $productData['price'],
                    'supplier_id' => $validatedData['supplier_id'],
                    'quantity' => 0, // Initialisation à 0 par défaut
                    'stock_threshold' => $productData['stock_threshold'] ?? 5, // Valeur par défaut
                ]);
            }

            // Rediriger avec un message de succès
            return redirect()->route('products.index')->with('success', 'Produits ajoutés avec succès.');
        } catch (\Exception $e) {
            // En cas d'erreur, rediriger avec un message d'erreur
            Log::error('Erreur lors de l\'ajout des produits : ', ['error' => $e->getMessage()]);
            return redirect()->route('products.index')->with('error', 'Une erreur est survenue lors de l\'ajout des produits.');
        }
    }


    

    /**
     * Affiche la liste des produits avec leurs fournisseurs associés.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Récupérer tous les produits et leurs fournisseurs associés
        $products = Product::with('supplier')->paginate(20); // Remplacez 10 par le nombre d'éléments à afficher par page
        
        // Retourner la vue index des produits
        return view('products.index', compact('products'));
    }

    /**
     * Méthode privée pour valider les données de produit.
     *
     * @param \Illuminate\Http\Request $request
     * @return array Validated data
     */
    private function validateProduct(Request $request)
    {
        return $request->validate([
            'name' => 'required|string|max:255', // Nom du produit requis
            'description' => 'nullable|string', // Description optionnelle
            'price' => 'required|numeric|min:0', // Prix positif requis
            'supplier_id' => 'required|exists:suppliers,id', // Doit être un ID valide
            'stock_threshold' => 'nullable|integer|min:5', // Seuil optionnel mais >= 5
        ]);
    }
}
