<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Product;
use Illuminate\Http\Request;

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
        $supplier = Supplier::findOrFail($id);
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
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
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', 'Fournisseur supprimé avec succès');
    }
}
