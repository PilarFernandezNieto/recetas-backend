<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\RecetaController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\DificultadController;
use App\Http\Controllers\IngredienteController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('admin')->middleware(['auth:sanctum', 'verified'])->group(function () {

    Route::apiResource('/ingredientes', IngredienteController::class);
    Route::apiResource('/categorias', CategoriaController::class);

    Route::get('/ingredientes-todos', [IngredienteController::class, 'ingredientesTodos']);

    Route::apiResource('/recetas', RecetaController::class);

    Route::get('/recetas-todas', [RecetaController::class, 'allRecetas'] );

    Route::apiResource('/dificultades', DificultadController::class);
});


Route::get('/recetas', [MainController::class, 'index']);
Route::get('/recetas/{receta}', [MainController::class, 'show']);
