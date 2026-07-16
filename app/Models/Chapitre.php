<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chapitre extends Model
{
    protected $fillable = [
        'matiere_id',
        'chapitre'
    ];

    public function matiere(): BelongsTo
    {
        return $this->belongsTo(Matiere::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }
}
