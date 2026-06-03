<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Configuracion;

class ConfiguracionesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $configuraciones = [
            [
                'clave' => 'horas_limite_visita',
                'valor' => '24',
                'tipo' => 'integer',
                'descripcion' => 'Horas para marcar visita como no realizada',
            ],
            [
                'clave' => 'visitas_por_periodo',
                'valor' => '13',
                'tipo' => 'integer',
                'descripcion' => 'Número de visitas cada dos meses por restaurante',
            ],
            [
                'clave' => 'dias_por_periodo',
                'valor' => '60',
                'tipo' => 'integer',
                'descripcion' => 'Días del período (2 meses)',
            ],
            [
                'clave' => 'distribucion_dias_requerida',
                'valor' => '1',
                'tipo' => 'boolean',
                'descripcion' => 'Requerir diversidad en días de la semana',
            ],
            [
                'clave' => 'distribucion_horarios_requerida',
                'valor' => '1',
                'tipo' => 'boolean',
                'descripcion' => 'Requerir diversidad en horarios (punta/normal/bajo)',
            ],
        ];

        foreach ($configuraciones as $config) {
            Configuracion::updateOrCreate(
                ['clave' => $config['clave']],
                $config
            );
        }
    }
}




