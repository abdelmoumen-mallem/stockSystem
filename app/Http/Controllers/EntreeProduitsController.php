<?php

namespace App\Http\Controllers;

use App\Models\Entree;
use Illuminate\Http\Request;

class EntreeProduitsController extends Controller
{
    public function store(Request $request)
    {
        // Valide les données du formulaire
        $validatedData = $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'entree_info_id' => 'required|exists:entree_info,numero_facture',
            'quantite' => 'required|integer',
            'prix_unitaire' => 'required|numeric',
            'emplacement_stockage' => 'nullable|exists:racks,id',
            'palette' => 'nullable|string|max:255',
            'date_peremption' => 'nullable|date',
            'commentaire' => 'nullable|string',
        ]);
        // Crée une nouvelle entrée avec les données validées
        $entree = Entree::create($validatedData);

        return response()->json(['message' => 'OK']);
    }
}
