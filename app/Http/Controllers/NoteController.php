<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matiere;
use App\Models\Note;
use App\Models\Chapitre;



class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
            $notes = Matiere::with('notes')->get();

            dd($notes);
            return view('notes.index', compact('notes'));
    }

    /**
     * Show the form for creating a new resource.
     */

public function create()
{
    $matieres = Matiere::with('chapitres')->get();
  
    return view('notes.create', compact('matieres'));
}
public function creates($chapitre)
{
    $chapitre = Chapitre::with('matiere')->findOrFail($chapitre);

    return view('notes.createinterne', compact('chapitre'));
}

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
Note::create([
    'chapitre_id' => $request->chapitre_id,
    'recto' => $request->recto,
    'verso' => $request->verso,
    'nombre_revision' => 0,
    'prochaine_revision' => today(),
]);

return redirect()
    ->back()
    ->withInput([
        'matiere_id' => $request->matiere_id,
        'chapitre_id' => $request->chapitre_id,
    ])
    ->with('success', 'Note ajoutée avec succès.');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
public function edit($id)
{
    $note = Note::findOrFail($id);

    return view('notes.edit', compact('note'));
}

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, $id)
{
    $request->validate([
        'recto' => 'required|string',
        'verso' => 'required|string',
    ]);

    $note = Note::findOrFail($id);
    $chapitreId = $note->chapitre_id;

    $note->update([
        'recto' => $request->recto,
        'verso' => $request->verso,
    ]);

    return redirect()
        ->route('chapitres.show', $chapitreId)
        ->with('success', 'Note modifiée avec succès.');
}

    /**
     * Remove the specified resource from storage.
     */
public function destroy($id)
{
    $note = Note::findOrFail($id);

    $chapitreId = $note->chapitre_id;

    $note->delete();

    return redirect()
        ->route('chapitres.show', $chapitreId)
        ->with('success', 'Note supprimée avec succès.');
}
}
