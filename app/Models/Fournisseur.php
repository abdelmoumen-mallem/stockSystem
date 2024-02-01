<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    protected $table = 'fournisseurs';

    protected $fillable = [
       
        'nom_entreprise',
        'nom_contact',
        'adresse',
        'telephone',
        'email',
        'site_web',
        'numero_tva',
        'notes',
        'ville',
        'code_postal',

    ];
}
