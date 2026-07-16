<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValidationEmploi extends Model
{
    protected $fillable = [
        'emploi_du_temps_id',
        'debut_semaine',
    ];

    public function emploiDuTemps()
    {
        return $this->belongsTo(EmploiDuTemps::class);
    }
}
