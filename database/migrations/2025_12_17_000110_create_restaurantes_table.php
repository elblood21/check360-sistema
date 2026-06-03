<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurantes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('direccion')->nullable();
            $table->unsignedBigInteger('ciudad_id')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->unsignedBigInteger('tipo_cocina_id')->nullable();
            $table->string('rango_ticket_promedio')->nullable();
            $table->unsignedInteger('capacidad_restaurante')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('ciudad_id');
            $table->index('tipo_cocina_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurantes');
    }
};






