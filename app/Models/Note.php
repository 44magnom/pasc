<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    protected $fillable = [
        'chapitre_id',
        'information',
        'recto',
        'verso',
        'nombre_revision',
        'prochaine_revision',
    ];

    public function chapitre(): BelongsTo
    {
        return $this->belongsTo(Chapitre::class);
    }
}