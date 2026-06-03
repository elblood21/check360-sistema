<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('preguntas_encuestas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('encuesta_id');
            $table->text('texto');
            $table->string('tipo_respuesta'); // 'escala_1_5', 'si_no', 'texto_libre', 'opciones'
            $table->integer('orden');
            $table->integer('dimension')->nullable();
            $table->text('opciones')->nullable(); // JSON para opciones múltiples
            $table->timestamps();
            $table->dateTime('deleted_at')->nullable();

            $table->foreign('encuesta_id')->references('id')->on('encuestas')->onDelete('cascade');
            $table->index('encuesta_id');
            $table->index('orden');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preguntas_encuestas');
    }
};




