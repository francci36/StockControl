<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function index()
    {
        // Paginer les résultats (par exemple, 10 fournisseurs par page)
        $suppliers = Supplier::paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }


    public function create()
    {
        if(auth()->user()->role !== 'admin' && auth()->user()->role !== 'manager') {
            abort(403, 'Accès interdit.');
        }

        $products = Product::all(); // récupère tous les produits
        return view('suppliers.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email',
            'telephone' => 'required|string|max:20',
        ]);
        // Créer le fournisseur
        $supplier = Supplier::create($validated);

        return redirect()->route('suppliers.index')->with('success', 'Fournisseur ajouté avec succès');
    }

    public function edit($id)
    {
        if(auth()->user()->role !== 'admin' && auth()->user()->role !== 'manager') {
            abort(403, 'Accès interdit.');
        }
        
        $supplier = Supplier::findOrFail($id);
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        if(auth()->user()->role !== 'admin' && auth()->user()->role !== 'manager') {
            abort(403, 'Accès interdit.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email,' . $id,
            'telephone' => 'required|string|max:20',
        ]);

        $supplier = Supplier::findOrFail($id);
        $supplier->update($validated);

        return redirect()->route('suppliers.index')->with('success', 'Fournisseur mis à jour avec succès');
    }

    public function destroy($id)
    {
        if(auth()->user()->role !== 'admin' && auth()->user()->role !== 'manager') {
            abort(403, 'Accès interdit.');
        }
        
        // Trouver le fournisseur
        $supplier = Supplier::findOrFail($id);

        // Supprimer les produits associés à ce fournisseur
        $supplier->products()->delete();

        // Supprimer le fournisseur
        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', 'Fournisseur supprimé avec succès.');
    }

    public function storeWithProducts(Request $request)
    {
        // Valider les données
        $validatedData = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'products' => 'required|array',
            'products.*.name' => 'required|string|max:255',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.stock_threshold' => 'nullable|integer|min:0',
            'products.*.description' => 'nullable|string', // Ajouter la description
        ]);

        DB::beginTransaction();

        try {
            // Trouver le fournisseur
            $supplier = Supplier::findOrFail($validatedData['supplier_id']);

            // Ajouter les produits associés
            foreach ($validatedData['products'] as $productData) {
                $supplier->products()->create([
                    'name' => $productData['name'],
                    'description' => $productData['description'] ?? null, // Description facultative
                    'price' => $productData['price'],
                    'stock_threshold' => $productData['stock_threshold'] ?? 5, // Valeur par défaut
                    'quantity' => 0, // Initialisation à 0 par défaut
                ]);
            }

            DB::commit(); // Valider la transaction
            return redirect()->route('suppliers.index')->with('success', 'Produits ajoutés avec succès.');
        } catch (\Exception $e) {
            DB::rollBack(); // Annuler la transaction en cas d'erreur
            Log::error('Erreur lors de l\'ajout des produits : ', ['error' => $e->getMessage()]);
            return redirect()->route('suppliers.index')->with('error', 'Une erreur est survenue lors de l\'ajout des produits.');
        }
    }

    

}
