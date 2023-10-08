<?php

use App\Http\Controllers\API\ContenedorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\VideoController;
use App\Http\Controllers\API\kmlContenedorController;
use App\Http\Controllers\API\KmlRutaController;
use App\Http\Controllers\API\RutaController;
use App\Http\Controllers\API\DetalleRutaController;
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
Route::prefix('kmlRuta')->group(function () {
    Route::get('/',[ KmlRutaController::class, 'getAll']);
    Route::post('/',[ KmlRutaController::class, 'create']);
    Route::delete('/{id}',[ KmlRutaController::class, 'delete']);
    Route::get('/{id}',[ KmlRutaController::class, 'get']);

    Route::get('/{idKmlRuta}/ruta', [RutaController::class, 'getAll']);
    Route::post('/{idKmlRuta}/ruta', [RutaController::class, 'create']);
    Route::delete('/{idKmlRuta}/ruta/{id}', [RutaController::class, 'delete']);
    Route::get('/{idKmlRuta}/ruta/{id}', [RutaController::class, 'get']);
    Route::put('/{idKmlRuta}/ruta/{id}', [RutaController::class, 'update']);
    Route::put('/{idKmlRuta}/ruta/{id}', [RutaController::class, 'updateSalto']);

    Route::get('/{idKmlRuta}/ruta/{id}/puntos', [RutaController::class, 'getPuntosRuta']);

   // Route::post('/{idKmlRuta}/ruta/detalleRuta', [DetalleRutaController::class, 'import']);

});   

Route::prefix('ruta')->group(function () {
    Route::post('/detalleRuta', [DetalleRutaController::class, 'import']);
    Route::get('/detalleRuta',[ DetalleRutaController::class, 'getAll']);
    Route::get('/{idRuta}/detalleRuta', [DetalleRutaController::class, 'get']);
    Route::put('/{idRuta}/detalleRuta/{id}', [DetalleRutaController::class, 'updateDetalleRuta']);
    Route::get('/{idRuta}/detalleRuta/{id}/frecuencias', [DetalleRutaController::class, 'getFrecuencias']);
});
Route::prefix('detalleRuta')->group(function () {
    Route::get('/{id}/frecuencias', [DetalleRutaController::class, 'getFrecuencias']);
    Route::put('/{id}/frecuencias', [DetalleRutaController::class, 'updateFrecuencias']);
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
