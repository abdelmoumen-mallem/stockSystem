<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entree extends Model
{
    protected $table = 'entrees';

    protected $fillable = [
        'entree_info_id',
        'produit_id',
        'quantite',
        'prix_unitaire',
        'commentaire',
        'emplacement_stockage',
        'date_peremption',
        'palette',
    ];

    public function entreeInfo()
    {
        return $this->belongsTo(EntreeInfo::class);
    }

    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    public function rack()
    {
        return $this->belongsTo(Rack::class);
    }
}
