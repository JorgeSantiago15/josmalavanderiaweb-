<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'nombre' => 'Empleada Matutina',
                'usuario' => 'matutino',
                'password' => Hash::make('123456'), // Contraseña temporal
                'tipo' => 'empleada',
                'turno_asignado' => 'matutino',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Empleada Vespertina',
                'usuario' => 'vespertino',
                'password' => Hash::make('123456'),
                'tipo' => 'empleada',
                'turno_asignado' => 'vespertino',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Gerente General',
                'usuario' => 'admin',
                'password' => Hash::make('admin123'),
                'tipo' => 'gerente',
                'turno_asignado' => 'matutino', // Irrelevante para el gerente
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}