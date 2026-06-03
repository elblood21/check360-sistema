<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            Perfiles::class,
            EstadosVisitasSeeder::class,
            ConfiguracionesSeeder::class,
            EncuestasSeeder::class,
            PreguntasEncuestasSeeder::class,
        ]);

        // Usuario de prueba (alineado a migración actual de users)
        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => \Hash::make('password'),
                'perfil_id' => 1,
                'estado' => 1,
            ]
        );
    }
}
