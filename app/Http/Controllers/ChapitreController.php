<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matiere;
use App\Models\Chapitre;


class ChapitreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Matiere $matiere)
{
    $chapitres = $matiere->chapitres()->orderBy('chapitre')->get();
    dd($matiere);

    return view('chapitre.gerer', compact('matiere', 'chapitres'));
}

    /**
     * Show the form for creating a new resource.
     */
public function create()
{
//
}
public function createForMatiere($matiere)
{
    $matiere = Matiere::with('chapitres.notes')
                      ->findOrFail($matiere);

    return view('chapitre.create', compact('matiere'));
}

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    
    $request->validate([
        'matiere_id' => 'required|exists:matieres,id',
        'chapitre' => 'required|string|max:255',
    ]);

    Chapitre::create([
        'matiere_id' => $request->matiere_id,
        'chapitre' => $request->chapitre,
    ]);
// dd($request->matiere_id);
    return redirect()
            ->back()
            ->with('success','Chapitre ajouté avec succès.');
}

    /**
     * Display the specified resource.
     */
public function show($id)
{
    $chapitre = Chapitre::with([
        'matiere',
        'notes' => function ($query) {
            $query->latest();
        }
    ])->findOrFail($id);

    return view('chapitre.show', compact('chapitre'));
}

    /**
     * Show the form for editing the specified resource.
     */
public function edit($id)
{
    $chapitre = Chapitre::findOrFail($id);
    // dd($chapitre);

    return view('chapitre.edit', compact('chapitre'));
}

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, $id)
{
    $request->validate([
        'chapitre' => 'required|string|max:255',
    ]);

    $chapitre = Chapitre::findOrFail($id);

    $chapitre->update([
        'chapitre' => $request->chapitre,
    ]);

    return redirect()
        ->route('chapitres.show', $chapitre->id)
        ->with('success', 'Chapitre modifié avec succès.');
}

    /**
     * Remove the specified resource from storage.
     */
public function destroy($id)
{
    $chapitre = Chapitre::findOrFail($id);

    $matiereId = $chapitre->matiere_id;

    $chapitre->delete();

    return redirect()
        ->route('chapitres.createForMatiere', $matiereId)
        ->with('success', 'Chapitre supprimé avec succès.');
}
}
