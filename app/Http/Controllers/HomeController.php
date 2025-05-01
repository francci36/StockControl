<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Redirection en fonction du rôle
        switch (auth()->user()->role) {
            case 'admin':
            case 'manager':
                return redirect()->route('dashboard');
            case 'user':
                return redirect()->route('user.home');
            default:
                abort(403, 'Accès interdit.');
        }
    }

}
        