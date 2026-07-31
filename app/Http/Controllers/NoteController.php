<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
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
    dd('bon');
  
    return view('notes.create', compact('matieres'));
}
public function creates($chapitre)
{
    $user = Auth::user();

    $chapitre = Chapitre::whereHas('matiere', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with('matiere')
        ->findOrFail($chapitre);

    // Limite de 5 notes pour les utilisateurs non abonnés
    if (!$user->is_subscribed && $chapitre->notes()->count() >= 5) {
        return redirect()
            ->back()
            ->with(
                'error',
                'La version gratuite est limitée à 5 notes par chapitre. Passez à la version Premium pour créer davantage de notes.'
            );
    }

    return view('notes.createinterne', compact('chapitre'));
}

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $user = Auth::user();

    $chapitre = Chapitre::whereHas('matiere', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->findOrFail($request->chapitre_id);

    // Limite de 5 notes par chapitre pour les utilisateurs non abonnés
    if (!$user->is_subscribed && $chapitre->notes()->count() >= 5) {
        return redirect()
            ->back()
            ->withInput()
            ->with(
                'error',
                'Limite atteinte: La version gratuite est limitée à 5 notes par chapitre. Passez à la version Premium pour créer davantage de notes.'
            );
    }

    Note::create([
        'chapitre_id'         => $chapitre->id,
        'recto'               => $request->recto,
        'verso'               => $request->verso,
        'nombre_revision'     => 0,
        'prochaine_revision'  => today(),
    ]);

// La note est créée ici...

$chapitre->refresh(); // Recharge les données du chapitre

if (!$user->is_subscribed && $chapitre->notes()->count() >= 5) {
    return redirect()
        ->route('chapitres.show', $chapitre->id)
        ->with('error', 'Vous avez atteint la limite de 5 notes pour ce chapitre.');
}

return redirect()
    ->back()
    ->withInput([
        'matiere_id'  => $request->matiere_id,
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
    $user = Auth::user();

    $note = Note::whereHas('chapitre.matiere', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->findOrFail($id);

    // Interdire la modification aux utilisateurs non abonnés
    if (!$user->is_subscribed) {
        return redirect()->back()->with(
            'error',
            'La modification des notes est réservée aux utilisateurs Premium.'
        );
    }

    session(['return_url' => url()->previous()]);

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
    $user = Auth::user();

    $note = Note::whereHas('chapitre.matiere', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->findOrFail($id);

    // Interdire la suppression aux utilisateurs non abonnés
    if (!$user->is_subscribed) {
        return redirect()
            ->back()
            ->with('error', 'La suppression des notes est réservée aux utilisateurs Premium.');
    }

    $chapitreId = $note->chapitre_id;

    $note->delete();

    return redirect()
        ->route('chapitres.show', $chapitreId)
        ->with('success', 'Note supprimée avec succès.');
}

public function toggle(Note $note)
{
    $note->is_revised = ! $note->is_revised;
    $note->save();

    return back()->with('success', 'Statut mis à jour.');
}
public function gererNote(Chapitre $chapitre)
{
    return view('notes.gerer', compact('chapitre'));
}
}
