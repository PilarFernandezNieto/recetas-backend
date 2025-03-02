<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RecetaController;
use App\Http\Controllers\DificultadController;
use App\Http\Controllers\IngredienteController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {

    Route::apiResource('/ingredientes', IngredienteController::class);

    Route::apiResource('/recetas', RecetaController::class);

    Route::apiResource('/dificultades', DificultadController::class);
});

