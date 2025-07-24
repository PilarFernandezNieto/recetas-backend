<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Receta;
use App\Traits\ImageHandler;
use Illuminate\Http\Request;
use App\Models\RecetaIngrediente;
use App\Http\Requests\RecetaRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\RecetaCollection;

class RecetaController extends Controller
{
    use ImageHandler;
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        // Obtener el término de búsqueda desde la URL (?buscar=...)
        $buscar = $request->query('buscar', '');

        // Construir la consulta base con relaciones y orden
        $query = Receta::with(['dificultad', 'categoria', 'ingredientes'])
            ->orderBy('recetas.nombre', 'ASC')
            ->join('categorias', 'categorias.id', '=', 'recetas.categoria_id') // Hacemos join para poder buscar por nombre de categoría
            ->select('recetas.*'); // Aseguramos que solo seleccionamos columnas de recetas (evita conflictos de columnas duplicadas)

        // Si hay término de búsqueda, aplicar filtro por nombre o categoría
        if ($buscar) {
            $query->where(function (Builder $q) use ($buscar) {
                $q->where('recetas.nombre', 'like', '%' . $buscar . '%')
                  ->orWhere('categorias.nombre', 'like', '%' . $buscar . '%');
            });
        }






        // Devolvemos la colección con la paginación
  return new RecetaCollection($query->paginate(3));
    }


    public function allRecetas()
    {
        return new RecetaCollection(Receta::with('dificultad')->with('categoria')->with('ingredientes')->orderBy('nombre', 'ASC')->get());
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
        $receta->categoria_id = $datos['categoria_id'];
        $receta->intro = $datos['intro'];
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
        // Almacena los ingredientes en la tabla pivote
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
        // Carga un ingrediente con los datos de las tablas relacionadas (dificultades - ingredientes)
        return $receta->load(['dificultad', 'ingredientes', 'categoria']);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(RecetaRequest $request, Receta $receta)
    {


        $datos = $request->validated();

        if ($request->hasFile('imagen')) {
            $this->borraImagen($receta->imagen);
            $imagen = $request->imagen->store('img', "public");
            $datos['imagen'] = Storage::url($imagen);
        } else {
            $datos['imagen'] = $receta->imagen;
        }
        $receta->update([
            'nombre' => $datos['nombre'],
            'origen' => $datos['origen'],
            'tiempo' => $datos['tiempo'],
            'comensales' => $datos['comensales'],
            'dificultad_id' => $datos['dificultad_id'],
            'categoria_id' => $datos['categoria_id'],
            'intro' => $datos['intro'],
            'instrucciones' => $datos['instrucciones'],
            'imagen' => $datos['imagen']
        ]);

        // Actualizar ingredientes
        if (isset($datos['ingredientes'])) {
            // Eliminar ingredientes existentes para evitar duplicados
            RecetaIngrediente::where('receta_id', $receta->id)->delete();

            $receta_ingrediente = [];
            foreach ($datos['ingredientes'] as $ingrediente) {
                $receta_ingrediente[] = [
                    'receta_id' => $receta->id,
                    'ingrediente_id' => $ingrediente['ingrediente_id'],
                    'cantidad' => $ingrediente['cantidad'],
                    'unidad' => $ingrediente['unidad'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }

            RecetaIngrediente::insert($receta_ingrediente);
        }
        return [
            "type" => "success",
            "message" => "Receta actualizada correctamente",
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Receta $receta)
    {
        RecetaIngrediente::where('receta_id', $receta->id)->delete();

        if ($receta->imagen) {
            $this->borraImagen($receta->imagen);
        }

        $receta->delete();
        return [
            "type" => "success",
            "message" => "Receta eliminada correctamente"
        ];
    }
}
