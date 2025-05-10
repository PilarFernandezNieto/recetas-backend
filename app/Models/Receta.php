<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receta extends Model
{

    protected $fillable = ['nombre', 'origen', 'tiempo', 'imagen', 'intro', 'instrucciones', 'comensales', 'categoria_id', 'dificultad_id'];

    public function dificultad()
    {
        return $this->belongsTo(Dificultad::class);
    }
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
    public function ingredientes()
    {
        return $this->belongsToMany(Ingrediente::class, 'receta_ingredientes')->withPivot('cantidad')->withPivot('unidad');
    }
}
