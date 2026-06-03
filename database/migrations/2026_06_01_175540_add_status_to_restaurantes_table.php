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
        Schema::table('restaurantes', function (Blueprint $table) {
            $table->integer('estado')->default(1)->after('capacidad_restaurante'); // 0=inactivo, 1=activo
            $table->integer('aprobado')->default(0)->after('estado'); // 0=pendiente, 1=aprobado, 2=rechazado
            $table->foreignId('aprobado_por')->nullable()->after('aprobado')->constrained('users')->onDelete('set null');
            $table->dateTime('aprobado_at')->nullable()->after('aprobado_por');
            
            $table->index('aprobado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurantes', function (Blueprint $table) {
            $table->dropForeign(['aprobado_por']);
            $table->dropIndex(['aprobado']);
            $table->dropColumn(['estado', 'aprobado', 'aprobado_por', 'aprobado_at']);
        });
    }
};
