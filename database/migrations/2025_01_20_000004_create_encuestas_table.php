<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('encuestas', function (Blueprint $table) {
            $table->id();
            $table->string('tipo'); // 'entrada' o 'salida'
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->integer('estado')->default(1);
            $table->timestamps();
            $table->dateTime('deleted_at')->nullable();

            $table->index('tipo');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('encuestas');
    }
};




