<?php

namespace App\Http\Controllers;
use App\Models\Matiere;
use App\Models\Note;
use App\Models\Chapitre;

use Illuminate\Http\Request;

class RevisionController extends Controller
{

public function revisionGenerale()
{
$notes = Note::with('chapitre.matiere')
    ->whereDate('prochaine_revision', '<=', today())
    ->inRandomOrder()
    ->get();

return view('matieres.revisiongenerale', compact('notes'));
}


public function show($id)
{
    $matiere = Matiere::findOrFail($id);

    $notes = Note::with('chapitre.matiere')
        ->whereHas('chapitre', function ($query) use ($id) {
            $query->where('matiere_id', $id);
        })
        ->inRandomOrder()
        ->get();

    return view('matieres.revision', compact('matiere', 'notes'));
}

public function chapitre($id)
{
    $chapitre = Chapitre::findOrFail($id);

    $notes = Note::with('chapitre.matiere')
                 ->where('chapitre_id', $id)
                 ->inRandomOrder()
                 ->get();

    return view('matieres.revisiongenerale', compact('notes', 'chapitre'));
}

public function valider()
{
    $notes = Note::whereDate('prochaine_revision', '<=', today())->get();

    foreach ($notes as $note) {

        $note->nombre_revision++;

        $intervalle = $this->intervalle($note->nombre_revision);

        $note->prochaine_revision = today()->addDays($intervalle);

        $note->save();
    }

    return redirect()->route('accueil')
        ->with('success', 'Révision validée.');
}

private function intervalle($revision)
{
    $intervalles = [
        1,
        1,
        2,
        3,
        4,
        5,
        6,
        7,
        8
    ];

    if ($revision <= count($intervalles)) {
        return $intervalles[$revision - 1];
    }

    $jours = 8;

    for ($i = 10; $i <= $revision; $i++) {
        $jours *= 2;
    }

    return $jours;
}
}
