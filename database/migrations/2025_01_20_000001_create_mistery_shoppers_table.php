<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mistery_shoppers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('telefono')->nullable();
            $table->text('observaciones')->nullable();
            $table->string('password')->nullable();
            $table->integer('estado')->default(1); // 0=inactivo, 1=activo
            $table->integer('aprobado')->default(0); // 0=pendiente, 1=aprobado
            $table->foreignId('aprobado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->dateTime('aprobado_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->dateTime('deleted_at')->nullable();

            $table->index('email');
            $table->index('aprobado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mistery_shoppers');
    }
};

