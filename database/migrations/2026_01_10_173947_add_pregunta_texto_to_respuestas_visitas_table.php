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
        Schema::table('respuestas_visitas', function (Blueprint $table) {
            $table->text('pregunta_texto')->nullable()->after('pregunta_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('respuestas_visitas', function (Blueprint $table) {
            $table->dropColumn('pregunta_texto');
        });
    }
};
