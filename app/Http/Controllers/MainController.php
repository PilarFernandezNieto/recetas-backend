<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use Illuminate\Http\Request;
use App\Http\Resources\RecetaCollection;
use Illuminate\Database\Eloquent\Builder;


class MainController extends Controller
{
     public function index(Request $request)
    {
        $buscar = $request->query('buscar', '');

        $query = Receta::with(['dificultad', 'categoria', 'ingredientes'])
            ->orderBy('recetas.nombre', 'ASC')
            ->join('categorias', 'categorias.id', '=', 'recetas.categoria_id')
            ->select('recetas.*');

        // 🔹 Si hay término de búsqueda, aplicamos filtro
        if ($buscar) {
            $query->where(function (Builder $q) use ($buscar) {
                $q->where('recetas.nombre', 'like', '%' . $buscar . '%')
                  ->orWhere('categorias.nombre', 'like', '%' . $buscar . '%');
            });
        }

        // 🔹 Obtenemos SQL real interpolando los bindings
        // $sql = vsprintf(
        //     str_replace('?', "'%s'", $query->toSql()),
        //     array_map('addslashes', $query->getBindings())
        // );
        // 🔹 MOSTRAR la SQL en Postman
        //return response()->json(['sql' => $sql]);

        // 🔹 Devolvemos la colección con paginación
        return new RecetaCollection($query->paginate(8));

    }

    public function show(Receta $receta){
        return $receta->load(['dificultad', 'categoria', 'ingredientes']);
    }
}
