<?php

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

//});
