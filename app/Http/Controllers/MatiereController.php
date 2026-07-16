<?php

namespace App\Http\Controllers;
use App\Models\Matiere;

use Illuminate\Http\Request;

class MatiereController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $matieres = Matiere::all();

    // dd($matieres);
        return view('matieres.index', compact('matieres'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $matieres=Matiere::all();
        return view('matieres.add', compact('matieres'));
    }

    /**
     * Store a newly created resource in storage.
     */

public function store(Request $request)
{
    $request->validate([
        'matiere' => 'required|string|max:255',
    ]);

    Matiere::create([
        'matiere' => $request->matiere,
    ]);

  $matieres = Matiere::all()->reverse();

    return view('matieres.add', compact('matieres'))
            ->with('success', 'Matière ajoutée avec succès.');
                    
}

    /**
     * Display the specified resource.
     */

public function show($id)
{
$matiere = Matiere::with('chapitres.notes')->findOrFail($id);

return view('matieres.show', compact('matiere'));
}

    /**
     * Show the form for editing the specified resource.
     */
public function edit($id)
{
    $matiere = Matiere::findOrFail($id);
    // dd($matiere);

    return view('matieres.edit', compact('matiere'));
}

    /**
     * Update the specified resource in storage.
     */
        public function update(Request $request, $id)
        {
            $request->validate([
                'matiere' => 'required|max:255'
            ]);

            $matiere = Matiere::findOrFail($id);

            $matiere->update([
                'matiere' => $request->matiere
            ]);

            return redirect()->route('matieres.create')
                            ->with('success', 'Matière modifiée avec succès.');
        }

    /**
     * Remove the specified resource from storage.
     */
public function destroy($id)
{
    $matiere = Matiere::findOrFail($id);

    $matiere->delete();

    return redirect()->route('matieres.create')
                     ->with('success', 'Matière supprimée avec succès.');
}
}
