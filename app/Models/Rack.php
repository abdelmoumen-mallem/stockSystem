<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rack extends Model
{
    protected $table = 'racks';

    protected $fillable = [
        'entree_id',
        'allee',
        'emplacement',
        'niveau',
        'statut',
        'categorie',
        'alveole',
    ];

    public function entree()
    {
        return $this->belongsTo(Entree::class);
    }
}
