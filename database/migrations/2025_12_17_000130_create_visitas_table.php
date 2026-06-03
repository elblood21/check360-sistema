<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mistery_shopper_id');
            $table->unsignedBigInteger('restaurante_id');
            $table->date('fecha_asignacion');
            $table->time('hora_asignacion');
            $table->unsignedBigInteger('estado_id')->default(1);
            $table->text('motivo_rechazo')->nullable();
            $table->integer('periodo_mes')->nullable();
            $table->integer('periodo_anio')->nullable();
            $table->string('tipo_horario')->nullable(); // 'punta', 'normal', 'bajo'
            $table->integer('dia_semana')->nullable(); // 1-7 (lunes-domingo)
            $table->timestamps();
            $table->dateTime('deleted_at')->nullable();

            $table->foreign('mistery_shopper_id')->references('id')->on('mistery_shoppers')->onDelete('restrict');
            $table->foreign('restaurante_id')->references('id')->on('restaurantes')->onDelete('restrict');
            $table->foreign('estado_id')->references('id')->on('estados_visitas')->onDelete('restrict');
            
            $table->index('mistery_shopper_id');
            $table->index('restaurante_id');
            $table->index('estado_id');
            $table->index('fecha_asignacion');
            $table->index(['periodo_mes', 'periodo_anio']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitas');
    }
};




