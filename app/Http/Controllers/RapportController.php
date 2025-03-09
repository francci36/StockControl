<?php

// app/Http/Controllers/RapportController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RapportController extends Controller
{
    public function index()
    {
        // Ajoutez ici la logique pour récupérer les données des rapports
        return view('rapports.index');
    }
}


