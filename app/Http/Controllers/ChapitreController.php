<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Matiere;
use App\Models\Chapitre;
use Exception;

class ChapitreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Matiere $matiere)
{
    $matiere = Auth::user()
        ->matieres()
        ->findOrFail($matiere->id);

    $chapitres = $matiere->chapitres()
        
        ->get();

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
    $user = Auth::user();

    $matiere = $user->matieres()
        ->with([
            'chapitres' => function ($query) {
                $query->orderBy('chapitre', 'asc')
                      ->with('notes');
            }
        ])
        ->findOrFail($matiere);


    return view('chapitre.create', compact('matiere'));
}
    /**
     * Store a newly created resource in storage.
     */
/**
 * Store a newly created resource in storage.
 */


public function store(Request $request)
{
    try {

        $request->validate([
            'matiere_id' => 'required|exists:matieres,id',
            'chapitre'   => 'required|string|max:255',
        ]);

        $user = Auth::user();

        // Vérifier que la matière appartient à l'utilisateur
        $matiere = $user->matieres()->findOrFail($request->matiere_id);

        // Limite de 5 chapitres pour les utilisateurs non abonnés
        if (!$user->is_subscribed && $matiere->chapitres()->count() >= 3) {
            return redirect()
                ->back()
                ->with('error', 'La version gratuite est limitée à 3 chapitres par matière. Passez à la version Premium pour créer davantage de chapitres.');
        }

        Chapitre::create([
            'matiere_id' => $matiere->id,
            'chapitre'   => $request->chapitre,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Chapitre ajouté avec succès.');

    } catch (Exception $e) {

        dd($e->getMessage());

    }
}

    /**
     * Display the specified resource.
     */
public function show($id)
{
    $chapitre = Chapitre::with([
        'matiere',
        'notes' => function ($query) {
            $query->orderBy('id', 'asc');
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
    ->route('chapitres.createForMatiere', $chapitre->matiere_id)
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
    ->back()
    ->with('success', 'Chapitre supprimé avec succès.');
}
}
