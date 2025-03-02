<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecetaRequest;
use App\Models\Receta;
use App\Models\RecetaIngrediente;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RecetaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RecetaRequest $request)
    {
        $datos = $request->validated();
        $receta = new Receta();
        $receta->nombre = $datos['nombre'];
        $receta->origen = $datos['origen'];
        $receta->tiempo = $datos['tiempo'];
        $receta->comensales = $datos['comensales'];
        $receta->dificultad_id = $datos['dificultad_id'];
        $receta->instrucciones = $datos['instrucciones'];
        $receta->save();

        // Obtener el id de la receta
        $id = $receta->id;

        // Obtener los ingredientes
        $ingredientes = $datos['ingredientes'];

        // Formatear los ingredientes
        $receta_ingrediente = [];
        foreach($ingredientes as $ingrediente){
            $receta_ingrediente[] = [
                'receta_id' => $id,
                'ingrediente_id' => $ingrediente['ingrediente_id'],
                'cantidad' => $ingrediente['cantidad'],
                'unidad' => $ingrediente['unidad'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
        }

        // Almacena los ingredientes
        RecetaIngrediente::insert($receta_ingrediente);

        return [
            "message" => "Guardando receta " . $receta->id,
            "receta" => $receta,
            "ingredientes" => $datos['ingredientes']
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(Receta $receta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Receta $receta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Receta $receta)
    {
        //
    }
}
