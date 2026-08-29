<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Note;
use App\Models\Matiere;
use App\Models\User;

class OfflineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
  public function synchroniserMatiere(Request $request)
    {

        try {


            \Log::info(
                'Synchronisation matière reçue',
                $request->all()
            );



            /*
            |--------------------------------------------------------------------------
            | Validation
            |--------------------------------------------------------------------------
            */

            $request->validate([


                'local_id' => [
                    'required',
                    'string'
                ],


                'matiere' => [
                    'required',
                    'string',
                    'max:255'
                ],


                'user_id' => [
                    'required',
                    'exists:users,id'
                ],


            ]);





            /*
            |--------------------------------------------------------------------------
            | Récupérer utilisateur
            |--------------------------------------------------------------------------
            */

            $user = User::find(
                $request->user_id
            );



            if (!$user) {


                return response()->json([

                    'success'=>false,

                    'message'=>'Utilisateur introuvable'

                ],404);


            }





            /*
            |--------------------------------------------------------------------------
            | Vérifier limite version gratuite
            |--------------------------------------------------------------------------
            */

            if (
                !$user->is_subscribed
            ) {


                $nombreMatieres =
                    $user->matieres()->count();



                if (
                    $nombreMatieres >= 3
                ) {


                    return response()->json([

                        'success'=>false,

                        'message'=>
                            'Limite gratuite atteinte : maximum 3 matières.'

                    ],403);


                }


            }





            /*
            |--------------------------------------------------------------------------
            | Création matière
            |--------------------------------------------------------------------------
            */

            $matiere =

                $user->matieres()->create([

                    'matiere' =>
                        $request->matiere

                ]);






            \Log::info(

                'Matière créée avec succès',

                [

                    'id' =>
                        $matiere->id,


                    'user_id' =>
                        $matiere->user_id

                ]

            );






            /*
            |--------------------------------------------------------------------------
            | Retour vers IndexedDB
            |--------------------------------------------------------------------------
            */

            return response()->json([


                'success'=>true,


                'local_id'=>
                    $request->local_id,



                'matiere'=>[


                    'id'=>
                        $matiere->id,


                    'matiere'=>
                        $matiere->matiere,


                    'user_id'=>
                        $matiere->user_id


                ]


            ]);





        }
        catch(\Throwable $e) {


            \Log::error(

                'Erreur synchronisation matière',

                [

                    'message'=>
                        $e->getMessage(),


                    'line'=>
                        $e->getLine()

                ]

            );



            return response()->json([

                'success'=>false,

                'message'=>
                    $e->getMessage()


            ],500);



        }


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

        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'telephone' => $user->telephone,
            'email' => $user->email,
            'is_active' => $user->is_active,
            'is_subscribed' => $user->is_subscribed,
        ],

        'matieres' => $matieres,
    ]);
}

public function notes()
{
    // premet de recupérer toutes les notes de l utilisateur depuis le serveur
    $notes = Note::whereHas('chapitre.matiere', function ($query) {
        $query->where('user_id', auth()->id());
    })
    ->with('chapitre.matiere')
    ->get();

    return response()->json([
        'success' => true,
        'notes' => $notes,
    ]);
}
}
