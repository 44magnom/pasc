<?php

use App\Http\Controllers\MatiereController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\RevisionController;
use App\Http\Controllers\AccueilController;
use App\Http\Controllers\EmploiDuTempsController;
use App\Http\Controllers\ValidationEmploi;

use App\Http\Controllers\ChapitreController;


use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('app');
// });

Route::resource('/matieres', MatiereController::class);
Route::resource('notes', NoteController::class);
Route::get('/matieres/{matiere}/notes/create', [NoteController::class, 'create'])
    ->name('notes.creat');

    Route::get('/revision/{matiere}', [RevisionController::class, 'show'])
     ->name('revision.show');

     Route::get('/revision-generale', [RevisionController::class, 'revisionGenerale'])
    ->name('revision.generale');


Route::get('/', [AccueilController::class, 'index'])->name('accueil');



Route::resource('chapitres', ChapitreController::class);

Route::get('/matieres/{matiere}/chapitres/nouveau', [ChapitreController::class, 'createForMatiere'])
    ->name('chapitres.createForMatiere');

Route::get('/revision/chapitre/{id}', [RevisionController::class, 'chapitre'])
    ->name('revision.chapitre');
Route::get('/chapitres/{chapitre}/notes/create', [NoteController::class, 'creates'])
    ->name('notes.creates');
Route::post('/revision/valider', [RevisionController::class, 'valider'])
    ->name('revision.valider');
Route::resource('emplois', EmploiDuTempsController::class);
Route::post(
    '/emplois/{emploi}/validation',
    [EmploiDuTempsController::class, 'toggleValidation']
)->name('emplois.validation');