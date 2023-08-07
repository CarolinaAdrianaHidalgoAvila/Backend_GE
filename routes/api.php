<?php

use App\Http\Controllers\API\ContenedorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\VideoController;
use App\Http\Controllers\API\kmlContenedorController;

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
Route::prefix('video')->group(function () {
    Route::get('/',[ VideoController::class, 'getAll']);
    Route::post('/',[ VideoController::class, 'create']);
    Route::delete('/{id}',[ VideoController::class, 'delete']);
    Route::get('/{id}',[ VideoController::class, 'get']);
    Route::put('/{id}',[ VideoController::class, 'update']);
});
Route::prefix('kmlContenedor')->group(function () {
    Route::get('/',[ kmlContenedorController::class, 'getAll']);
    Route::post('/',[ kmlContenedorController::class, 'create']);
    Route::delete('/{id}',[ kmlContenedorController::class, 'delete']);
    Route::get('/{id}',[ kmlContenedorController::class, 'get']);

    Route::get('/{idKmlContenedor}/contenedor', [ContenedorController::class, 'getAll']);
    Route::post('/{idKmlContenedor}/contenedor', [ContenedorController::class, 'create']);
    Route::delete('/{idKmlContenedor}/contenedor/{id}', [ContenedorController::class, 'delete']);
    Route::get('/{idKmlContenedor}/contenedor/{id}', [ContenedorController::class, 'get']);
    Route::put('/{idKmlContenedor}/contenedor/{id}', [ContenedorController::class, 'update']);
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
