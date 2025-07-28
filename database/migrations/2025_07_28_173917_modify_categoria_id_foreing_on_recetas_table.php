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
        Schema::table('recetas', function (Blueprint $table) {
            // Elimina la restricción de clave foránea existente
            $table->dropForeign(['categoria_id']);

            // Crea una nueva clave foránea sin onDelete("cascade")
            $table->foreign('categoria_id')->references('id')->on('categorias');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recetas', function (Blueprint $table) {
            // Elimina la clave foránea creada en el método up
            $table->dropForeign(['categoria_id']);

            // Vuelve a crear la clave foránea con onDelete("cascade")
            $table->foreign('categoria_id')->references('id')->on('categorias')->onDelete('cascade');
        });

    }
};
