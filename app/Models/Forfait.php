<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Forfait extends Model
{

    protected $fillable = [
        'nom',
        'duree',
        'montant',
        'description',
        'is_active',
    ];

    
    public function abonnements()
{
    return $this->hasMany(Abonnement::class);
}
}
