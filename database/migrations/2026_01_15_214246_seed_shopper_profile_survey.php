<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $encuestaId = DB::table('encuestas')->insertGetId([
            'tipo' => 'perfil_shopper',
            'nombre' => 'Perfil del Mistery Shopper',
            'descripcion' => 'Cuestionario inicial para conocer el perfil del shopper.',
            'estado' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $preguntas = [
            [
                'encuesta_id' => $encuestaId,
                'texto' => '¿Cuál es tu rango de edad?',
                'tipo_respuesta' => 'seleccion_unica',
                'opciones' => json_encode(['18-25', '26-35', '36-45', '46-60', 'Más de 60']),
                'orden' => 1,
                'dimension' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'encuesta_id' => $encuestaId,
                'texto' => '¿Cuál es tu ocupación principal?',
                'tipo_respuesta' => 'texto_corto',
                'opciones' => null,
                'orden' => 2,
                'dimension' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'encuesta_id' => $encuestaId,
                'texto' => '¿Con qué frecuencia sales a comer a restaurantes?',
                'tipo_respuesta' => 'seleccion_unica',
                'opciones' => json_encode(['Diario', '2-3 veces por semana', 'Una vez por semana', 'Quincenal', 'Mensual']),
                'orden' => 3,
                'dimension' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'encuesta_id' => $encuestaId,
                'texto' => '¿Qué es lo que más valoras en una experiencia gastronómica?',
                'tipo_respuesta' => 'texto_largo',
                'opciones' => null,
                'orden' => 4,
                'dimension' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('preguntas_encuestas')->insert($preguntas);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $encuesta = DB::table('encuestas')->where('tipo', 'perfil_shopper')->first();
        if ($encuesta) {
            DB::table('preguntas_encuestas')->where('encuesta_id', $encuesta->id)->delete();
            DB::table('encuestas')->where('id', $encuesta->id)->delete();
        }
    }
};
