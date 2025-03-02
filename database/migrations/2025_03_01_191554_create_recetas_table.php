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
        Schema::create('recetas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->mediumText('instrucciones');
            $table->unsignedBigInteger('dificultad_id')->nullable();
            $table->foreign('dificultad_id')->references('id')->on('dificultades')->onDelete('cascade');
            $table->integer('tiempo');
            $table->integer('comensales');
            $table->string('imagen')->nullable();
            $table->string('origen');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recetas');
    }
};
