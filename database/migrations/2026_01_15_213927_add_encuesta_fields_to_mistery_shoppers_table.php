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
        Schema::table('mistery_shoppers', function (Blueprint $table) {
            $table->boolean('respondio_encuesta')->default(0)->after('aprobado_at');
            $table->json('respuestas_perfil')->nullable()->after('respondio_encuesta');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mistery_shoppers', function (Blueprint $table) {
            $table->dropColumn(['respondio_encuesta', 'respuestas_perfil']);
        });
    }
};
