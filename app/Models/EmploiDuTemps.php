<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmploiDuTemps extends Model
{
    protected $table = 'emplois_du_temps';

    protected $fillable = [
        'jour_id',
        'matiere_id',
        'commentaire',
    ];

    public function jour()
    {
        return $this->belongsTo(Jour::class);
    }

    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }
    public function validations()
{
    return $this->hasMany(ValidationEmploi::class);
}
}
