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
        // Vérifier si l'utilisateur a le rôle approprié
        if (auth()->user()->role !== 'admin' && auth()->user()->role !== 'manager') {
            abort(403, 'Accès interdit.');
        }
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
            'products.*.quantity' => 'nullable|integer|min:0',
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
    public function index(Request $request)
    {
        // Démarrer la requête avec les relations des fournisseurs et du stock
        $query = Product::with(['supplier', 'stock']);

        // Vérifier si l'utilisateur veut voir uniquement les produits en stock faible
        if ($request->has('lowStock') && $request->lowStock == 1) {
            $query->whereHas('stock', function ($q) {
                $q->whereColumn('quantity', '<=', 'stock_threshold');
            });
        }

        // Assurer que les données stock sont bien récupérées
        $products = $query->paginate(20);

        // Retourner la vue des produits avec les stocks affichés correctement
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

    public function storefront(Request $request)
    {
        // Récupérer toutes les catégories
        $categories = \App\Models\Category::all();
        
        // Récupérer la catégorie active
        $activeCategory = null;
        if ($request->has('category')) {
            $activeCategory = \App\Models\Category::find($request->category);
        }

        // Construire la requête de produits
        $query = Product::query()
            ->whereHas('stock', function($query) {
                $query->where('quantity', '>', 0);
            })
            ->with('categories');

        // Filtrer par catégorie si spécifié
        if ($request->filled('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('id', $request->category);
            });
        }

        // Filtrer par recherche si spécifié
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%'.$searchTerm.'%')
                ->orWhere('description', 'like', '%'.$searchTerm.'%');
            });
        }

        // Paginer les résultats (20 par page)
        $products = $query->paginate(20);

        return view('storefront.index', compact('products', 'categories', 'activeCategory'));
    }



}
