<?php

namespace App\Http\Controllers;
use App\Models\Matiere;
use App\Models\Note;
use App\Models\Chapitre;
use Carbon\Carbon;

use Illuminate\Http\Request;

class RevisionController extends Controller
{

public function revisionDuJour()
{
    $notes = Note::with('chapitre.matiere')
        ->whereDate('prochaine_revision', today())
        ->inRandomOrder()
        ->get();

    $typeRevision = 'jour';

    return view('matieres.revisiongenerale', compact('notes', 'typeRevision'));
}

public function revisionAnciennes()
{
    $notes = Note::with('chapitre.matiere')
        ->whereDate('prochaine_revision', '<', today())
        ->inRandomOrder()
        ->get();

    $typeRevision = 'anciennes';

    return view('matieres.revisiongenerale', compact('notes', 'typeRevision'));
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





public function valider(Request $request)
{
    if ($request->type === 'jour') {
        $notes = Note::whereDate('prochaine_revision', today())->get();
    } else {
        $notes = Note::whereDate('prochaine_revision', '<', today())->get();
    }

    foreach ($notes as $note) {

        $note->nombre_revision++;

        $intervalle = $this->intervalle($note->nombre_revision);

        $dateRevision = Carbon::parse($note->prochaine_revision);

        // Nombre de jours de retard
        $retard = $dateRevision->diffInDays(today());

        if ($retard <= 1) {
            // Aujourd'hui ou seulement 1 jour de retard
            $dateReference = $dateRevision;
        } else {
            // Plus d'un jour de retard : on repart d'aujourd'hui
            $dateReference = today();
        }

        $note->prochaine_revision = $dateReference->copy()->addDays($intervalle);

        $note->save();
    }

    return redirect()->route('accueil')
        ->with('success', 'Révision validée avec succès.');
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
