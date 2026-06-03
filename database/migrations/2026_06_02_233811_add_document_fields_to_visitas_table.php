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
        Schema::table('visitas', function (Blueprint $table) {
            $table->string('documento_tipo')->nullable()->after('total_pagado'); // 'boleta', 'factura'
            $table->string('documento_numero')->nullable()->after('documento_tipo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitas', function (Blueprint $table) {
            $table->dropColumn(['documento_tipo', 'documento_numero']);
        });
    }
};
