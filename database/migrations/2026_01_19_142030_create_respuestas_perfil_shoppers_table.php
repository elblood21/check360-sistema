<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('respuestas_perfil_shoppers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mistery_shopper_id');
            $table->unsignedBigInteger('pregunta_id');
            $table->text('respuesta_texto')->nullable();
            $table->string('respuesta_valor')->nullable(); // Para valores numéricos u opciones
            $table->timestamps();

            $table->foreign('mistery_shopper_id')->references('id')->on('mistery_shoppers')->onDelete('cascade');
            $table->foreign('pregunta_id')->references('id')->on('preguntas_encuestas')->onDelete('cascade');
            
            $table->index('mistery_shopper_id');
            $table->index('pregunta_id');
            
            // Evitar respuestas duplicadas
            $table->unique(['mistery_shopper_id', 'pregunta_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('respuestas_perfil_shoppers');
    }
};
