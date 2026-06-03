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
            $table->boolean('plan_activo')->default(false)->after('capacidad_restaurante');
            $table->date('plan_inicio')->nullable()->after('plan_activo');
            $table->date('plan_fin')->nullable()->after('plan_inicio');
            $table->date('periodo_inicio')->nullable()->after('plan_fin');
            $table->date('periodo_fin')->nullable()->after('periodo_inicio');
            $table->integer('porcentaje_descuento')->default(50)->after('periodo_fin');
            $table->string('carta_tipo')->default('url')->after('porcentaje_descuento');
            $table->string('carta_url')->nullable()->after('carta_tipo');
            $table->text('carta_imagenes')->nullable()->after('carta_url');
            $table->string('logo')->nullable()->after('carta_imagenes');
            $table->text('imagenes')->nullable()->after('logo');
            $table->string('social_facebook')->nullable()->after('imagenes');
            $table->string('social_instagram')->nullable()->after('social_facebook');
            $table->string('social_tiktok')->nullable()->after('social_instagram');
            $table->text('horario_peak')->nullable()->after('social_tiktok');
        });

        Schema::table('visitas', function (Blueprint $table) {
            $table->string('cupon_codigo')->nullable()->after('visitado_at');
            $table->decimal('total_consumo', 10, 2)->nullable()->after('cupon_codigo');
            $table->decimal('total_descuento', 10, 2)->nullable()->after('total_consumo');
            $table->decimal('total_pagado', 10, 2)->nullable()->after('total_descuento');
            $table->dateTime('cupon_canjeado_at')->nullable()->after('total_pagado');
            
            $table->index('cupon_codigo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurantes', function (Blueprint $table) {
            $table->dropColumn([
                'plan_activo',
                'plan_inicio',
                'plan_fin',
                'periodo_inicio',
                'periodo_fin',
                'porcentaje_descuento',
                'carta_tipo',
                'carta_url',
                'carta_imagenes',
                'logo',
                'imagenes',
                'social_facebook',
                'social_instagram',
                'social_tiktok',
                'horario_peak'
            ]);
        });

        Schema::table('visitas', function (Blueprint $table) {
            $table->dropIndex(['cupon_codigo']);
            $table->dropColumn([
                'cupon_codigo',
                'total_consumo',
                'total_descuento',
                'total_pagado',
                'cupon_canjeado_at'
            ]);
        });
    }
};
