<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProduitsController extends Controller
{
    public function index($id)
    {
        // Récupérez la liste de tous les produits
        $produits = Produit::where('fournisseur_id', $id)->orderBy('nom_produit')->get();

        return response()->json(['produits' => $produits]);
    }


    public function store(Request $request)
    {
        // Valider les données du formulaire
        $validatedData = $request->validate([
            'reference' => 'nullable|string|max:255|unique:produits',
            'nom_produit' => 'required|string|max:255|unique:produits',
            'description' => 'nullable|string|max:255',
            'categorie' => 'nullable|string|max:255',
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'image_url' => 'nullable|string|max:255|unique:produits',
            'code_barres' => 'nullable|string|max:255|unique:produits',
            'notes' => 'nullable|string',
        ]);

        // Créer un nouveau produit avec les données validées
        $produit = Produit::create($validatedData);

        return response()->json(['message' => 'OK']);
    }

    public function show($id)
    {
        // Récupérer les détails d'un produit par son ID
        $produit = Produit::find($id);

        if (!$produit) {
            return response()->json(['message' => 'Produit non trouvé'], 404);
        }

        return response()->json(['produit' => $produit]);
    }

    public function update(Request $request, $id)
    {
        // Récupérer le produit à mettre à jour
        $produit = Produit::findOrFail($id);

        // Valider les données du formulaire
        $validatedData = $request->validate([
            'reference' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('produits')->ignore($produit->id),
            ],
            'nom_produit' => [
                'required',
                'string',
                'max:255',
                Rule::unique('produits')->ignore($produit->id),
            ],
            'description' => 'nullable|string|max:255',
            'categorie' => 'nullable|string|max:255',
            'image_url' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('produits')->ignore($produit->id),
            ],
            'code_barres' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('produits')->ignore($produit->id),
            ],
            'notes' => 'nullable|string',
            'status' => 'nullable|numeric',
            'e_commerce' => 'nullable|numeric',
        ]);

        // Mettre à jour les données du produit avec les données validées
        $produit->update($validatedData);

        return response()->json(['message' => 'ok']);
    }

    public function destroy($id)
    {
        // Recherchez le produit par son ID
        $produit = Produit::find($id);

        if (!$produit) {
            return response()->json(['message' => 'Produit non trouvé'], 404);
        }

        // Supprimez le produit
        $produit->delete();

        return response()->json(['message' => 'Produit supprimé avec succès']);
    }
}

