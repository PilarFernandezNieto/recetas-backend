<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receta extends Model
{

    protected $fillable = ['nombre', 'origen', 'tiempo', 'imagen', 'intro', 'instrucciones', 'comensales'];

    public function dificultad()
    {
        return $this->belongsTo(Dificultad::class);
    }
    public function ingredientes() {
        return $this->belongsToMany(Ingrediente::class, 'receta_ingredientes')->withPivot('cantidad')->withPivot('unidad');

    }
}
