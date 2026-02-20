<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'nombre' => 'Admin',
            'apellidos' => 'Barbería',
            'edad' => 30,
            'telefono' => '5551234567',
            'email' => 'admin@barberia.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'tipo_cliente' => 'permanente',
            'activo' => true,
        ]);

        // Cliente Permanente
        User::create([
            'nombre' => 'Juan',
            'apellidos' => 'Pérez López',
            'edad' => 25,
            'telefono' => '5559876543',
            'email' => 'cliente@example.com',
            'password' => Hash::make('cliente123'),
            'role' => 'cliente',
            'tipo_cliente' => 'permanente',
            'activo' => true,
        ]);

        // Cliente Temporal
        User::create([
            'nombre' => 'Carlos',
            'apellidos' => 'González Méndez',
            'edad' => 28,
            'telefono' => '5551112233',
            'email' => 'temporal@example.com',
            'password' => Hash::make('temporal123'),
            'role' => 'cliente',
            'tipo_cliente' => 'temporal',
            'activo' => true,
        ]);

        // Más clientes de ejemplo
        User::create([
            'nombre' => 'María',
            'apellidos' => 'Rodríguez',
            'edad' => 22,
            'telefono' => '5554445566',
            'email' => 'maria@example.com',
            'password' => Hash::make('password'),
            'role' => 'cliente',
            'tipo_cliente' => 'permanente',
            'activo' => true,
        ]);

        User::create([
            'nombre' => 'Pedro',
            'apellidos' => 'Sánchez',
            'edad' => 35,
            'telefono' => '5557778899',
            'email' => 'pedro@example.com',
            'password' => Hash::make('password'),
            'role' => 'cliente',
            'tipo_cliente' => 'temporal',
            'activo' => true,
        ]);
    }
}
