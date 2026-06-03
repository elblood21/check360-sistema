<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Encuesta;

class EncuestasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $encuestas = [
            [
                'tipo' => 'entrada',
                'nombre' => 'Encuesta de Entrada - Expectativas',
                'descripcion' => 'Encuesta que debe ser completada en un plazo máximo de 24 horas antes de la visita al restaurante asignado. Responde todas las preguntas basándote en tus expectativas generales del restaurante.',
                'estado' => 1,
            ],
            [
                'tipo' => 'salida',
                'nombre' => 'Encuesta de Salida - Experiencia Real',
                'descripcion' => 'Encuesta que debe ser completada en un plazo máximo de 24 horas después de la visita al restaurante asignado. Responde todas las preguntas basándote en tu experiencia real vivida durante la visita.',
                'estado' => 1,
            ],
        ];

        foreach ($encuestas as $encuesta) {
            Encuesta::updateOrCreate(
                ['tipo' => $encuesta['tipo']],
                $encuesta
            );
        }
    }
}




