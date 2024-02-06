<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $table = 'produits';

    protected $fillable = [
        'reference',
        'nom_produit',
        'description',
        'categorie',
        'prix_achat',
        'prix_vente',
        'quantite_min_stock',
        'quantite_stock',
        'fournisseur_id',
        'image_url_1',
        'image_url_2',
        'image_url_3',
        'code_barres',
        'taxes',
        'remises',
        'status',
        'e_commerce',
        'notes',
        'activate_image_2',
        'activate_image_3',
    ];

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }
}
