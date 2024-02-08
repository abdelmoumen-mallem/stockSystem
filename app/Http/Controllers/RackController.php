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
        '*.allee' => 'required|string',
        '*.emplacement' => 'required|string',
        '*.niveau' => 'required|string',
        '*.alveole' => 'required|string',
        '*.statut' => 'required|integer',
    ]);

    // Crée une nouvelle entrée pour chaque ensemble de données
    foreach ($validatedData as $data) {
        $rack = Rack::create($data);
    }

    return response()->json(['message' => 'OK']);
}
}
