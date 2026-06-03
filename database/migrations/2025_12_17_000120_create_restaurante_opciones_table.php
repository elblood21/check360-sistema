<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurante_opciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('restaurante_id');
            $table->string('clave');
            $table->json('valor_json')->nullable();
            $table->text('valor_texto')->nullable();
            $table->timestamps();

            $table->index('restaurante_id');
            $table->index('clave');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurante_opciones');
    }
};






