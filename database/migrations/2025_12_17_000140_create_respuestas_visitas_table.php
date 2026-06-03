<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('respuestas_visitas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('visita_id');
            $table->unsignedBigInteger('pregunta_id');
            $table->text('respuesta_texto')->nullable();
            $table->string('respuesta_valor')->nullable(); // Para valores numéricos u opciones
            $table->string('encuesta_tipo'); // 'entrada' o 'salida'
            $table->timestamps();

            $table->foreign('visita_id')->references('id')->on('visitas')->onDelete('cascade');
            $table->foreign('pregunta_id')->references('id')->on('preguntas_encuestas')->onDelete('cascade');
            
            $table->index('visita_id');
            $table->index('pregunta_id');
            $table->index('encuesta_tipo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('respuestas_visitas');
    }
};




