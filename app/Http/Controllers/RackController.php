<?php

namespace App\Http\Controllers;

use App\Models\Rack;
use Illuminate\Http\Request;

class RackController extends Controller
{
    public function store(Request $request)
    {
        // Valide les données du formulaire
        $validatedData = $request->validate([
            'allee' => 'required|string',
            'emplacement' => 'required|string',
            'niveau' => 'required|string',
            'statut' => 'required|integer',
        ]);
        // Crée une nouvelle entrée avec les données validées
        $rack = Rack::create($validatedData);

        return response()->json(['message' => 'OK']);
    }
}
