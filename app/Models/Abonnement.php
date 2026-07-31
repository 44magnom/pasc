<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Abonnement extends Model
{

    protected $fillable = [
        'user_id',
        'forfait_id',
        'date_debut',
        'date_fin',
        'statut',
        'reference_paiement',
    ];

    
    public function user()
{
    return $this->belongsTo(User::class);
}

public function forfait()
{
    return $this->belongsTo(Forfait::class);
}
}
