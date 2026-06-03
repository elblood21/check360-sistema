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
            $table->boolean('notificado_24horas')->default(0)->after('estado_id');
            $table->timestamp('notificado_24horas_at')->nullable()->after('notificado_24horas');
            $table->boolean('notificado_2horas')->default(0)->after('notificado_24horas_at');
            $table->timestamp('notificado_2horas_at')->nullable()->after('notificado_2horas');
            $table->boolean('notificado_post')->default(0)->after('notificado_2horas_at');
            $table->timestamp('notificado_post_at')->nullable()->after('notificado_post');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('visitas', function (Blueprint $table) {
            $table->dropColumn([
                'notificado_24horas',
                'notificado_24horas_at',
                'notificado_2horas',
                'notificado_2horas_at',
                'notificado_post',
                'notificado_post_at'
            ]);
        });
    }
};
