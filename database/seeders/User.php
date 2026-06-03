<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class User extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('users')->insert([
            'id'=>1,
            'name' => 'Sebastian Alvarado',
            'rut' => '19.816.948-k',
            'email' => 'sebastian@e-chile.cl',
            'password' => \Hash::make('123456'),
            'perfil_id' => 1,
            'estado' => 1
        ]);
    }
}
