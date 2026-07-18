<?php

namespace App\Http\Controllers;
use App\Models\Matiere;
use App\Models\Note;

use Illuminate\Http\Request;

class AccueilController extends Controller
{
public function index()
{
    $notes = Note::all();

    $matieres = Matiere::all();

$notesAReviser = Note::whereDate('prochaine_revision', today())->count();

$notesEnRetard = Note::whereDate('prochaine_revision', '<', today())->count();



    return view('accueil', compact(
        'notes',
        'matieres',
        'notesAReviser',
        'notesEnRetard'
    ));
}
}
