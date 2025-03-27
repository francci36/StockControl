<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function dashboard()
    {
        $orders = Auth::user()->orders; // Exemple de récupération des commandes
        return view('user.dashboard', compact('orders'));
    }

}
