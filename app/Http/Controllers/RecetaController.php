<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Receta;
use Illuminate\Http\Request;
use App\Models\RecetaIngrediente;
use App\Http\Requests\RecetaRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\RecetaCollection;

class RecetaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new RecetaCollection(Receta::with('dificultad')->with('ingredientes')->get());
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(RecetaRequest $request)
    {
        $datos = $request->validated();
        $receta = new Receta();
        $imagen = $request->imagen->store('img', "public");
        $datos['imagen'] = Storage::url($imagen);
        $receta->nombre = $datos['nombre'];
        $receta->origen = $datos['origen'];
        $receta->tiempo = $datos['tiempo'];
        $receta->comensales = $datos['comensales'];
        $receta->dificultad_id = $datos['dificultad_id'];
        $receta->instrucciones = $datos['instrucciones'];
        $receta->imagen = $datos['imagen'];

        $receta->save();

        // Obtener el id de la receta
        $id = $receta->id;

        // Obtener los ingredientes
        $ingredientes = $datos['ingredientes'];

        // Formatear los ingredientes
        $receta_ingrediente = [];
        foreach ($ingredientes as $ingrediente) {
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
            "type" => "success",
            "message" => "Receta guardada correctamente",
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(Receta $receta)
    {
        return $receta->load(['dificultad', 'ingredientes']);
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
