<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
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
            'edad' => 35,
            'telefono' => '5551234567',
            'email' => 'admin@barberia.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'tipo_cliente' => null,
            'activo' => true,
            'email_verified_at' => now(),
        ]);

        // Cliente Permanente
        User::create([
            'nombre' => 'Juan',
            'apellidos' => 'Pérez García',
            'edad' => 28,
            'telefono' => '5559876543',
            'email' => 'juan.perez@example.com',
            'password' => Hash::make('cliente123'),
            'role' => 'cliente',
            'tipo_cliente' => 'permanente',
            'activo' => true,
            'email_verified_at' => now(),
        ]);

        // Cliente Permanente 2
        User::create([
            'nombre' => 'Carlos',
            'apellidos' => 'Rodríguez López',
            'edad' => 32,
            'telefono' => '5551112233',
            'email' => 'carlos.rodriguez@example.com',
            'password' => Hash::make('cliente123'),
            'role' => 'cliente',
            'tipo_cliente' => 'permanente',
            'activo' => true,
            'email_verified_at' => now(),
        ]);

        // Cliente Temporal
        User::create([
            'nombre' => 'Miguel',
            'apellidos' => 'Hernández Sánchez',
            'edad' => 25,
            'telefono' => '5554445566',
            'email' => 'miguel.hernandez@example.com',
            'password' => Hash::make('temporal123'),
            'role' => 'cliente',
            'tipo_cliente' => 'temporal',
            'activo' => true,
            'email_verified_at' => now(),
        ]);

        // Cliente Temporal 2
        User::create([
            'nombre' => 'Luis',
            'apellidos' => 'Martínez González',
            'edad' => 22,
            'telefono' => '5557778899',
            'email' => 'luis.martinez@example.com',
            'password' => Hash::make('temporal123'),
            'role' => 'cliente',
            'tipo_cliente' => 'temporal',
            'activo' => true,
            'email_verified_at' => now(),
        ]);

        // Cliente Temporal 3
        User::create([
            'nombre' => 'Pedro',
            'apellidos' => 'López Ramírez',
            'edad' => 30,
            'telefono' => '5553334455',
            'email' => 'pedro.lopez@example.com',
            'password' => Hash::make('temporal123'),
            'role' => 'cliente',
            'tipo_cliente' => 'temporal',
            'activo' => true,
            'email_verified_at' => now(),
        ]);

        // Cliente inactivo (ejemplo)
        User::create([
            'nombre' => 'José',
            'apellidos' => 'García Torres',
            'edad' => 40,
            'telefono' => '5556667788',
            'email' => 'jose.garcia@example.com',
            'password' => Hash::make('inactivo123'),
            'role' => 'cliente',
            'tipo_cliente' => 'temporal',
            'activo' => false,
            'email_verified_at' => now(),
        ]);
    }
}
