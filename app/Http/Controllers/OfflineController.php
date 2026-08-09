<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Note;

class OfflineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */


public function store(Request $request)
{
    try {

        \Log::info('Synchronisation reçue', $request->all());

        $request->validate([
            'chapitre_id' => 'required|exists:chapitres,id',
            'recto' => 'required',
            'verso' => 'required',
        ]);

        $note = Note::create([
            'chapitre_id'        => $request->chapitre_id,
            'recto'              => $request->recto,
            'verso'              => $request->verso,
            'nombre_revision'    => $request->nombre_revision ?? 0,
            'prochaine_revision' => $request->prochaine_revision ?? today(),
            'is_revised'         => $request->is_revised ?? true,
        ]);

        \Log::info('Note créée avec succès', [
            'id' => $note->id,
        ]);

        return response()->json([
            'success' => true,
            'id' => $note->id,
        ]);

    } catch (\Throwable $e) {

        \Log::error('Erreur synchronisation', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ]);

        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
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

    public function sync()
{
    $user = auth()->user();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Utilisateur non authentifié.'
        ], 401);
    }

    $matieres = $user->matieres()
        ->with('chapitres.notes')
        ->get();

    return response()->json([
        'success' => true,
        'matieres' => $matieres,
    ]);
}
}
