<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProduitsController extends Controller
{
    public function index($id)
    {
        // Récupére la liste de tous les produits
        $produits = Produit::where('fournisseur_id', $id)->orderBy('nom_produit')->get();

        return response()->json(['produits' => $produits]);
    }


    public function store(Request $request)
    {
        // Valide les données du formulaire
        $validatedData = $request->validate([
            'reference' => 'nullable|string|max:255|unique:produits',
            'nom_produit' => 'nullable|required|string|max:255|unique:produits',
            'description' => 'nullable|string|max:255',
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'image_url_1' => 'string|max:255|unique:produits',
            'image_url_2' => 'string|max:255|unique:produits',
            'image_url_3' => 'string|max:255|unique:produits',
            'code_barres' => 'nullable|string|max:255|unique:produits',
            'notes' => 'nullable|string',
            'prix_achat' => 'numeric',
            'prix_vente' => 'numeric',
            'quantite_min_stock' => 'integer',
            'taxes' => 'numeric',
            'remises' => 'numeric',
        ]);

        // Crée un nouveau produit avec les données validées
        $produit = Produit::create($validatedData);

        for ($i = 1; $i <= 3; $i++) {
            $imageDataColumn = "image_url_{$i}_image";
            $fileNameColumn = "image_url_$i";
            $imageData = $request->input($imageDataColumn);
            $fileName = $request->input($fileNameColumn);
    
            if ($imageData && $fileName) {
                list(, $imageData) = explode(',', $imageData);
                $imageBinaryData = base64_decode($imageData);
                file_put_contents(public_path("images/produits/$fileName"), $imageBinaryData);
            }
        }

        return response()->json(['message' => 'OK']);
    }

    public function show($id)
    {
        // Récupére les détails d'un produit par son ID
        $produit = Produit::find($id);

        if (!$produit) {
            return response()->json(['message' => 'Produit non trouvé'], 404);
        }

        return response()->json(['produit' => $produit]);
    }

    public function update(Request $request, $id)
    {
        // Récupére le produit à mettre à jour
        $produit = Produit::findOrFail($id);

        $test = $request->input("activate_image_2");


        // Valide les données du formulaire
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
            'code_barres' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('produits')->ignore($produit->id),
            ],
            'notes' => 'nullable|string',
            'status' => 'nullable|numeric',
            'e_commerce' => 'nullable|numeric',
            'prix_achat' => 'numeric',
            'prix_vente' => 'numeric',
            'quantite_min_stock' => 'integer',
            'taxes' => 'numeric',
            'remises' => 'numeric',
            'activate_image_2' => 'integer',
            'activate_image_3' => 'integer',
        ]);

        // Mettre à jour les données du produit avec les données validées
        $produit->update($validatedData);

        for ($i = 1; $i <= 3; $i++) {
            $imageDataColumn = "image_url_{$i}_image";
            $fileNameColumn = "image_url_$i";
            $fileNewNameColumn = "image_url_update_$i";

            $imageData = $request->input($imageDataColumn);
            $fileName = $request->input($fileNameColumn);
            $fileNewName = $request->input($fileNewNameColumn);
    
            if ($imageData && $fileNewName) {
                list(, $imageData) = explode(',', $imageData);
                $imageBinaryData = base64_decode($imageData);
                file_put_contents(public_path("images/produits/$fileNewName"), $imageBinaryData);

                $filePath = public_path("images/produits/$fileName");
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                $produit->{"image_url_$i"} = $fileNewName;
                $produit->save();
            }
        }

        return response()->json(['message' => "OK"]);
    }

    public function destroy($id)
    {
        // Recherche le produit par son ID
        $produit = Produit::find($id);

        if (!$produit) {
            return response()->json(['message' => 'Produit non trouvé'], 404);
        }

        for ($i = 1; $i <= 3; $i++) {
            $imageUrlColumn = "image_url_$i";
            $imageUrl = $produit->{$imageUrlColumn};
            if ($imageUrl) {
                $filePath = public_path("images/produits/$imageUrl");
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
        }

        $produit->delete();

        return response()->json(['message' => 'Produit supprimé avec succès']);
    }
}

