<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EstadoVisita;

class EstadosVisitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $estados = [
            [
                'id' => 1,
                'nombre' => 'Pendiente',
                'descripcion' => 'Visita creada, esperando encuesta de entrada',
            ],
            [
                'id' => 2,
                'nombre' => 'En espera de visita',
                'descripcion' => 'Encuesta de entrada respondida, esperando que el shopper realice la visita',
            ],
            [
                'id' => 3,
                'nombre' => 'Visita completada',
                'descripcion' => 'Ya hizo visita',
            ],
            [
                'id' => 4,
                'nombre' => 'Finalizada',
                'descripcion' => 'Ambas encuestas respondidas',
            ],
            [
                'id' => 5,
                'nombre' => 'No se realizó',
                'descripcion' => 'Automático después de 24 horas sin completar',
            ],
            [
                'id' => 6,
                'nombre' => 'Rechazada',
                'descripcion' => 'Shopper rechazó la visita con motivo',
            ],
        ];

        foreach ($estados as $estado) {
            EstadoVisita::updateOrCreate(
                ['id' => $estado['id']],
                $estado
            );
        }
    }
}




