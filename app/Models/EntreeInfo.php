<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntreeInfo extends Model
{
    protected $table = 'entree_info';

    protected $fillable = [
        'fournisseur_id',
        'numero_facture',
        'transporteur',
        'plaque_immat',
        'commentaire',
    ];

    public function fournisseur()
    {
        return $this->belongsTo(Fournisseur::class);
    }
}


