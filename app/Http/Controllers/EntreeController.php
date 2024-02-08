<?php

namespace App\Http\Controllers;

use App\Models\EntreeInfo;
use App\Models\Produit;
use Illuminate\Http\Request;

class EntreeController extends Controller
{
    public function store(Request $request)
    {
        // Valide les données du formulaire
        $validatedData = $request->validate([
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'numero_facture' => 'required|string|max:255|unique:entree_info',
            'transporteur' => 'required|string|max:255',
            'plaque_immat' => 'required|string|max:255',
            'commentaire' => 'nullable|string',
        ]);

        // Crée un nouveau produit avec les données validées
        $produit = EntreeInfo::create($validatedData);

        return response()->json(['message' => 'OK']);
    }

    public function index($id)
{
    // Récupérer l'entree_info avec le même numéro de facture
    $entreeInfo = EntreeInfo::where('numero_facture', $id)
        ->selectRaw('transporteur, plaque_immat, commentaire')
        ->first();

    // Charger les produits associés avec le même fournisseur_id
    $produits = Produit::join('entree_info', 'produits.fournisseur_id', '=', 'entree_info.fournisseur_id')
        ->selectRaw('produits.id, produits.reference, produits.nom_produit, produits.prix_achat, produits.code_barres')
        ->where('entree_info.numero_facture', $id)
        ->get();

    return response()->json([
        'entree_info' => $entreeInfo,
        'produits' => $produits,
    ]);
}

}
