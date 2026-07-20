<?php

namespace App\Http\Controllers;
use App\Models\Matiere;
use App\Models\Note;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class AccueilController extends Controller
{
public function index()
{
    if (!Auth::check()) {
        return view('front.bienvenu');
    }

    $user = Auth::user();

    $matieres = $user->matieres()->with(['chapitres', 'notes'])->get();

    $notes = Note::whereHas('chapitre.matiere', function ($query) use ($user) {
        $query->where('user_id', $user->id);
    })->get();

    $notesAReviser = Note::whereHas('chapitre.matiere', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->whereDate('prochaine_revision', today())
        ->count();

    $notesEnRetard = Note::whereHas('chapitre.matiere', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->whereDate('prochaine_revision', '<', today())
        ->count();

    return view('accueil', compact(
        'notes',
        'matieres',
        'notesAReviser',
        'notesEnRetard'
    ));
}
}
