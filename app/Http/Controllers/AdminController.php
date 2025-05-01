<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Accès interdit.');
        }

        $users = User::all(); // Récupère tous les utilisateurs
        return view('admin.dashboard', compact('users'));
    }
    public function createUser(Request $request)
    {
        // Vérifier les permissions
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Accès interdit.');
        }

        // Valider les données
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:user,manager,admin',
        ]);

        // Enregistrement avec gestion des erreurs
        try {
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => bcrypt($validatedData['password']),
                'role' => $validatedData['role'],
            ]);

            return redirect()->route('admin.users')->with('success', 'Utilisateur créé avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la création de l\'utilisateur : ' . $e->getMessage());
        }
    }

    public function showCreateUserForm()
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Accès interdit.');
        }

        return view('admin.create_user'); // Assure-toi que cette vue existe bien
    }

    public function listUsers()
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Accès interdit.');
        }

        $users = User::all(); // Récupère tous les utilisateurs
        return view('admin.users_list', compact('users')); // Redirige vers la vue
    }




    


}
