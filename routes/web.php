<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AccueilController;
use App\Http\Controllers\ChapitreController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmploiDuTempsController;
use App\Http\Controllers\MatiereController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RevisionController;

// Accueil
Route::get('/', [AccueilController::class, 'index'])->name('accueil');
Route::view('/bienvenu', 'front.bienvenu')->name('bienvenu');

// Routes protégées
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Profil Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Matières
    Route::resource('matieres', MatiereController::class);

    // Notes
    Route::resource('notes', NoteController::class);
    Route::get('/matieres/{matiere}/notes/create', [NoteController::class, 'create'])
        ->name('notes.creat');

    Route::get('/chapitres/{chapitre}/notes/create', [NoteController::class, 'creates'])
        ->name('notes.creates');

    // Révisions
    Route::get('/revision/{matiere}', [RevisionController::class, 'show'])
        ->name('revision.show');

    Route::get('/revision-generale', [RevisionController::class, 'revisionGenerale'])
        ->name('revision.generale');

    Route::get('/revision/chapitre/{id}', [RevisionController::class, 'chapitre'])
        ->name('revision.chapitre');

    Route::post('/revision/valider', [RevisionController::class, 'valider'])
        ->name('revision.valider');

    // Chapitres
    Route::resource('chapitres', ChapitreController::class);

    Route::get('/matieres/{matiere}/chapitres/nouveau', [ChapitreController::class, 'createForMatiere'])
        ->name('chapitres.createForMatiere');

    // Emplois du temps
    Route::resource('emplois', EmploiDuTempsController::class);

    Route::post('/emplois/{emploi}/validation', [EmploiDuTempsController::class, 'toggleValidation'])
        ->name('emplois.validation');
Route::get('/revision1/jour', [RevisionController::class, 'revisionDuJour'])
    ->name('revision.jour');
Route::get('/revision2/jour', [RevisionController::class, 'revisionAnciennes'])
    ->name('revision.anciennes');
});



// Routes d'authentification Breeze
require __DIR__.'/auth.php';