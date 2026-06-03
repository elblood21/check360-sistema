<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Perfiles extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('perfiles')->updateOrInsert(
            ['id' => 1],
            ['name' => 'Superadministrador', 'created_at' => now(), 'updated_at' => now()]
        );

        \DB::table('perfiles')->updateOrInsert(
            ['id' => 2],
            ['name' => 'Administrador', 'created_at' => now(), 'updated_at' => now()]
        );
    }
}
