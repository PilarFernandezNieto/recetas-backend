<?php

namespace App\Http\Controllers;

use App\Models\Ingrediente;
use App\Traits\ImageHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\IngredienteRequest;
use App\Http\Resources\IngredienteCollection;


class IngredienteController extends Controller
{
    use ImageHandler;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new IngredienteCollection(Ingrediente::orderBy('nombre', 'ASC')->paginate(5));
        //return new IngredienteCollection(Ingrediente::orderBy('id', 'DESC')->paginate(3));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IngredienteRequest $request)
    {
        $datos = $request->validated();

        $imagen = $request->imagen->store('img', "public");
        $datos['imagen'] = Storage::url($imagen);

        $ingrediente = Ingrediente::create([
            'nombre' => $datos['nombre'],
            'imagen' => $datos['imagen'],
            'descripcion' => $datos['descripcion'],
        ]);

        return [
            "type" => "success",
            "ingrediente" => $ingrediente,
            "message" => "Ingrediente creado correctamente"
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(Ingrediente $ingrediente)
    {
        return $ingrediente;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IngredienteRequest $request, Ingrediente $ingrediente)
    {
        $datos = $request->validated();

        if ($request->hasFile('imagen')) {
            $this->borraImagen($ingrediente->imagen);
            $imagen = $request->imagen->store('img', "public");
            $datos['imagen'] = Storage::url($imagen);
        } else {
            $datos['imagen'] = $ingrediente->imagen;
        }

        $ingrediente->update([
            'nombre' => $datos['nombre'],
            'imagen' => $datos['imagen'],
            'descripcion' => $datos['descripcion']
        ]);

        return [
            "type" => "success",
            "ingrediente" => $ingrediente,
            "message" => "Ingrediente actualizado correctamente"
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ingrediente $ingrediente)
    {
        $this->borraImagen($ingrediente->imagen);

        $ingrediente->delete();
        return [
            "type" => "success",
            "message" => "Ingrediente eliminado correctamente"
        ];
    }
}
