<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('receta_ingredientes', function (Blueprint $table) {
            // Elimina la restricción de clave foránea existente
            $table->dropForeign(['ingrediente_id']);

            // Crea una nueva clave foránea sin onDelete("casacde")
            $table->foreign('ingrediente_id')->references('id')->on('ingredientes');


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receta_ingredientes', function (Blueprint $table) {
            // Elimina la clave foránea creada en el método up
            $table->dropForeign(['ingrediente_id']);

            // Vuelve a crear la clave foránea con onDelete("cascade")
            $table->foreign('ingrediente_id')->references('id')->on('ingredientes')->onDelete('cascade');
        });
    }
};
