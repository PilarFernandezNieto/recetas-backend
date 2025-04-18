<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use Illuminate\Http\Request;
use App\Http\Resources\RecetaCollection;

class MainController extends Controller
{
    public function index(){
        return new RecetaCollection(Receta::with('dificultad', 'ingredientes')->get());

    }

    public function show(Receta $receta){
        return $receta->load(['dificultad', 'ingredientes']);
    }
}
