<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DimensionEncuesta;

class DimensionEncuestaSeeder extends Seeder
{
    public function run(): void
    {
        $dimensiones = [
            [
                'id' => 1,
                'nombre' => 'Calidad de Comida',
                'color' => '#ff4d4d',
                'icono' => 'fa-cutlery',
                'orden' => 1
            ],
            [
                'id' => 2,
                'nombre' => 'Servicio',
                'color' => '#ff944d',
                'icono' => 'fa-user',
                'orden' => 2
            ],
            [
                'id' => 3,
                'nombre' => 'Ambiente y Limpieza',
                'color' => '#ffdb4d',
                'icono' => 'fa-trash',
                'orden' => 3
            ],
            [
                'id' => 4,
                'nombre' => 'Tiempos de Entrega',
                'color' => '#77dd77',
                'icono' => 'fa-clock-o',
                'orden' => 4
            ],
            [
                'id' => 5,
                'nombre' => 'Relación Precio-Calidad',
                'color' => '#2ecc71',
                'icono' => 'fa-money',
                'orden' => 5
            ],
            [
                'id' => 6,
                'nombre' => 'Protocolos de Seguridad',
                'color' => '#3498db',
                'icono' => 'fa-shield',
                'orden' => 6
            ],
            [
                'id' => 7,
                'nombre' => 'Experiencia General',
                'color' => '#9b59b6',
                'icono' => 'fa-star',
                'orden' => 7
            ],
        ];

        foreach ($dimensiones as $dim) {
            DimensionEncuesta::updateOrCreate(['id' => $dim['id']], $dim);
        }
    }
}
