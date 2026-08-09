<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Abonnement\AbonnementController;
use App\Http\Controllers\Paiement\PaydunyaController;
// use App\Http\Controllers\AbonnementController;
use App\Http\Controllers\OfflineController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/paydunya/callback/{forfait}', [PaydunyaController::class, 'callback'])
    ->name('paydunya.callback');
Route::post('/offline/note', [OfflineController::class, 'store'])
    ->name('offline.note');

Route::middleware('auth')->get('/offline/sync', [
    OfflineController::class,
    'sync'
]);