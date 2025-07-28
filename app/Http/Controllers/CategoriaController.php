<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoriaRequest;
use App\Http\Resources\CategoriaCollection;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new CategoriaCollection(Categoria::orderBy('nombre', 'ASC')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoriaRequest $request)
    {
        $datos = $request->validated();

        $categoria = Categoria::create([
            'nombre' => $datos['nombre']
        ]);

        return [
            "type" => "success",
            "categoria" => $categoria,
            "message" => "Categoría creada correctamente"
        ];
    }

    /**
     * Display the specified resource.
     */
    public function show(Categoria $categoria)
    {
        return $categoria;
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(CategoriaRequest $request, Categoria $categoria)
    {

        $datos = $request->validated();

        $categoria->update([
            'nombre' => $datos['nombre']
        ]);

        return [
            "type" => "success",
            "categoria" => $categoria,
            "message" => "Categoría actualizada correctamente"
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categoria $categoria)
    {
        try {
            $categoria->delete();
            return response()->json([
                "type" => "success",
                "message" => "Categoría eliminada correctamente"
            ]);
        } catch (\Exception $e) {

            if ($e->getCode() == 23000) { // Integrity constraint violation
                return response()->json([
                    "type" => "error",
                    "code" => $e->getCode(),
                    "message" => "No se puede eliminar la categoría porque está siendo utilizada por otros recursos."
                ], 409);
            }
        }
    }
}
