<?php

namespace App\Http\Controllers;
use App\Models\EmploiDuTemps;
use App\Models\Matiere;
use App\Models\ValidationEmploi;
use App\Models\Jour;
use Illuminate\Http\Request;
use Carbon\Carbon;


class EmploiDuTempsController extends Controller
{
    /**
     * Display a listing of the resource.
     */

public function index()
{
    $ordreJours = [
        'Lundi' => 1,
        'Mardi' => 2,
        'Mercredi' => 3,
        'Jeudi' => 4,
        'Vendredi' => 5,
        'Samedi' => 6,
        'Dimanche' => 7,
    ];

    $debutSemaine = Carbon::now()
        ->startOfWeek(Carbon::MONDAY)
        ->toDateString();

    $emplois = EmploiDuTemps::with([
        'jour',
        'matiere',
        'validations' => function ($query) use ($debutSemaine) {
            $query->where('debut_semaine', $debutSemaine);
        }
    ])
    ->get()
    ->sortBy(function ($emploi) use ($ordreJours) {
        return $ordreJours[$emploi->jour->nom] ?? 99;
    })
    ->groupBy('jour.nom');

    return view('emploiDuTemps.index', compact('emplois'));
}
    /**
     * Show the form for creating a new resource.
     */
public function create()
{
    $jours = Jour::all();
    $matieres = Matiere::all();

    return view('emploiDuTemps.create', compact('jours', 'matieres'));
}

public function store(Request $request)
{
    $request->validate([
        'jour_id' => 'required|exists:jours,id',
        'matiere_id' => 'required|exists:matieres,id',
        'commentaire' => 'nullable|string|max:1000',
    ]);

    $existe = EmploiDuTemps::where('jour_id', $request->jour_id)
        ->where('matiere_id', $request->matiere_id)
        ->exists();

    if ($existe) {
        return back()
            ->withInput()
            ->with('error', 'Cette matière est déjà programmée pour ce jour.');
    }

    EmploiDuTemps::create([
        'jour_id' => $request->jour_id,
        'matiere_id' => $request->matiere_id,
        'commentaire' => $request->commentaire,
    ]);

    return redirect()
        ->route('emplois.index')
        ->with('success', 'Matière ajoutée à l’emploi du temps.');
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function toggleValidation(EmploiDuTemps $emploi)
{
    // Lundi de la semaine actuelle
    $debutSemaine = Carbon::now()
        ->startOfWeek(Carbon::MONDAY)
        ->toDateString();

    $validation = ValidationEmploi::where(
            'emploi_du_temps_id',
            $emploi->id
        )
        ->where('debut_semaine', $debutSemaine)
        ->first();

    if ($validation) {

        // Si déjà validé → décocher
        $validation->delete();

    } else {

        // Sinon → valider
        ValidationEmploi::create([
            'emploi_du_temps_id' => $emploi->id,
            'debut_semaine' => $debutSemaine,
        ]);

    }

    return back();
}
}
