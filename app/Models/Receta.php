<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receta extends Model
{

    protected $fillable = ['nombre', 'origen', 'tiempo', 'imagen', 'instrucciones', 'comensales'];

    public function dificultad()
    {
        return $this->belongsTo(Dificultad::class);
    }
}
