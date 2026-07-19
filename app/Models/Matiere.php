<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Matiere extends Model
{
       protected $table = 'matieres';

protected $fillable = [
    'user_id',
    'matiere',
    'nbr_note',
];

    public function chapitres(): HasMany
    {
        return $this->hasMany(Chapitre::class);
    }



public function notes(): HasManyThrough
{
    return $this->hasManyThrough(
        Note::class,
        Chapitre::class
    );
}

public function user()
{
    return $this->belongsTo(User::class);
}
}
