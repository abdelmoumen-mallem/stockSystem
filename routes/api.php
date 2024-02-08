<?php

use App\Http\Controllers\EntreeController;
use App\Http\Controllers\EntreeProduitsController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\ProduitsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:sanctum')->group(function () {

    Route::get('/fournisseurs', [FournisseurController::class, 'index']);
    Route::post('/fournisseurs', [FournisseurController::class, 'store']);
    Route::put('/fournisseurs/{id}', [FournisseurController::class, 'update']);
    Route::delete('/fournisseurs/{id}', [FournisseurController::class, 'destroy']);

    Route::get('/produits/{id}', [ProduitsController::class, 'index']);
    Route::post('/produits', [ProduitsController::class, 'store']);
    Route::put('/produits/{id}', [ProduitsController::class, 'update']);
    Route::delete('/produits/{id}', [ProduitsController::class, 'destroy']);

    Route::get('/entrees/{id}', [EntreeController::class, 'index']);
    Route::post('/entrees', [EntreeController::class, 'store']);
    Route::put('/entrees/{id}', [EntreeController::class, 'update']);
    Route::delete('/entrees/{id}', [EntreeController::class, 'destroy']);

    Route::get('/entreesProduits/{id}', [EntreeProduitsController::class, 'index']);
    Route::post('/entreesProduits', [EntreeProduitsController::class, 'store']);
    Route::put('/entreesProduits/{id}', [EntreeProduitsController::class, 'update']);
    Route::delete('/entreesProduits/{id}', [EntreeProduitsController::class, 'destroy']);


//});
