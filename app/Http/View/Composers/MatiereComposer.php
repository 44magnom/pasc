<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use App\Models\Matiere;

class MatiereComposer
{
    public function compose(View $view)
    {
        $matieres = Matiere::with('chapitres')->get();

        $view->with([
            'matieres' => $matieres
        ]);
    }
}