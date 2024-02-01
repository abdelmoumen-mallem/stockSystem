<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FournisseurController extends Controller
{
    public function index()
    {
        // Récupérez la liste de tous les fournisseurs
        $fournisseurs = Fournisseur::all();

        return response()->json(['fournisseurs' => $fournisseurs]);
    }


    public function store(Request $request)
    {
        // Valider les données du formulaire
        $validatedData = $request->validate([
            'nom_entreprise' => 'required|string|max:255|unique:fournisseurs',
            'nom_contact' => 'nullable|string|max:255',
            'adresse' => 'nullable|string|max:255',
            'telephone' => 'nullable|string|max:255|unique:fournisseurs',
            'email' => 'nullable|string|email|max:255|unique:fournisseurs',
            'site_web' => 'nullable|string|max:255|unique:fournisseurs',
            'numero_tva' => 'nullable|string|max:255|unique:fournisseurs',
            'notes' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:255',
            'code_postal' => 'nullable|string|max:255',
        ]);

        // Créer un nouveau fournisseur avec les données validées
        $fournisseur = Fournisseur::create($validatedData);

        return response()->json(['message' => 'OK']);
    }

    public function show($id)
    {
        // Récupérer les détails d'un fournisseur par son ID
        $fournisseur = Fournisseur::find($id);

        if (!$fournisseur) {
            return response()->json(['message' => 'Fournisseur non trouvé'], 404);
        }

        return response()->json(['fournisseur' => $fournisseur]);
    }

    public function update(Request $request, $id)
    {
        // Récupérer le fournisseur à mettre à jour
        $fournisseur = Fournisseur::findOrFail($id);

        // Valider les données du formulaire
        $validatedData = $request->validate([
            'nom_entreprise' => [
                'required',
                'string',
                'max:255',
                Rule::unique('fournisseurs')->ignore($fournisseur->id),
            ],
            'nom_contact' => 'nullable|string|max:255',
            'adresse' => 'nullable|string|max:255',
            'telephone' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('fournisseurs')->ignore($fournisseur->id),
            ],
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                Rule::unique('fournisseurs')->ignore($fournisseur->id),
            ],
            'site_web' => [
                'nullable',
                'string',
                'max:255',
                'url',
                Rule::unique('fournisseurs')->ignore($fournisseur->id),
            ],
            'numero_tva' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('fournisseurs')->ignore($fournisseur->id),
            ],
            'notes' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:255',
            'code_postal' => 'nullable|string|max:255',
        ]);

        // Mettre à jour les données du fournisseur avec les données validées
        $fournisseur->update($validatedData);

        return response()->json(['message' => 'ok']);
    }

    public function destroy($id)
    {
        // Recherchez le fournisseur par son ID
        $fournisseur = Fournisseur::find($id);

        if (!$fournisseur) {
            return response()->json(['message' => 'Fournisseur non trouvé'], 404);
        }

        // Supprimez le fournisseur
        $fournisseur->delete();

        return response()->json(['message' => 'Fournisseur supprimé avec succès']);
    }
}
