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
        Schema::create('receta_ingredientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receta_id')->constrained()->onDelete('cascade');
            $table->foreignId('ingrediente_id')->constrained()->onDelete('cascade');
            $table->integer('cantidad');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receta_ingredientes');
    }
};
