<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class IngredienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ingredientes')->insert([
            [
                'nombre' => 'Tomate',
                'descripcion' => 'Rojo y jugoso, ideal para ensaladas y salsas.',
                'imagen' => 'https://picsum.photos/300/200',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nombre' => 'Cebolla',
                'descripcion' => 'Bulbo aromático usado en múltiples recetas.',
                'imagen' => 'https://picsum.photos/300/200',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nombre' => 'Ajo',
                'descripcion' => 'Condimento esencial con un sabor fuerte y característico.',
                'imagen' => 'https://picsum.photos/300/200',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nombre' => 'Pimiento',
                'descripcion' => 'Disponible en varios colores, aporta dulzura y textura.',
                'imagen' => 'https://picsum.photos/300/200',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nombre' => 'Zanahoria',
                'descripcion' => 'Verdura anaranjada rica en betacarotenos.',
                'imagen' => 'https://picsum.photos/300/200',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
