<?php

use App\Http\Controllers\FournisseurController;
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
    Route::get('/fournisseurs/{id}', [FournisseurController::class, 'show']);
    Route::put('/fournisseurs/{id}', [FournisseurController::class, 'update']);
    Route::delete('/fournisseurs/{id}', [FournisseurController::class, 'destroy']);

//});
