<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Requerimientos extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('requerimientos')->insert([
            'id'=>1,
            'name' => 'Contrato de trabajo',
            'descripcion' => 'descripcion de contrato',
            'estado'=>1
        ]);

        \DB::table('requerimientos')->insert([
            'id'=>2,
            'name' => 'Anexos',
            'descripcion' => 'descripcion de anexos',
            'estado'=>1
        ]);

        \DB::table('requerimientos')->insert([
            'id'=>3,
            'name' => 'Liquidaciones',
            'descripcion' => 'descripcion de liquidaciones',
            'estado'=>1
        ]);

        \DB::table('requerimientos')->insert([
            'id'=>4,
            'name' => 'Registro de asistencia',
            'descripcion' => 'descripcion de r. asistencias',
            'estado'=>1
        ]);

        \DB::table('requerimientos')->insert([
            'id'=>5,
            'name' => 'Planillas de cotizaciones',
            'descripcion' => 'descripcion de p. cotizaciones',
            'estado'=>1
        ]);
    }
}
