<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Servicio;

class ServicioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $servicios = [
            [
                'nombre' => 'Corte de Cabello Caballero',
                'descripcion' => 'Corte de cabello profesional con tijera y máquina. Incluye lavado y secado.',
                'precio_base' => 150.00,
                'duracion_minutos' => 45,
                'activo' => true,
            ],
            [
                'nombre' => 'Corte de Cabello + Barba',
                'descripcion' => 'Corte de cabello completo más arreglo de barba. Incluye lavado.',
                'precio_base' => 200.00,
                'duracion_minutos' => 60,
                'activo' => true,
            ],
            [
                'nombre' => 'Arreglo de Barba',
                'descripcion' => 'Perfilado y arreglo de barba con navaja y máquina.',
                'precio_base' => 80.00,
                'duracion_minutos' => 30,
                'activo' => true,
            ],
            [
                'nombre' => 'Rapado Completo',
                'descripcion' => 'Rapado completo con máquina. Incluye perfilado.',
                'precio_base' => 120.00,
                'duracion_minutos' => 30,
                'activo' => true,
            ],
            [
                'nombre' => 'Corte de Cabello Niño',
                'descripcion' => 'Corte de cabello para niños menores de 12 años.',
                'precio_base' => 100.00,
                'duracion_minutos' => 30,
                'activo' => true,
            ],
            [
                'nombre' => 'Tinte de Cabello',
                'descripcion' => 'Aplicación de tinte completo. Incluye corte de cabello.',
                'precio_base' => 350.00,
                'duracion_minutos' => 90,
                'activo' => true,
            ],
            [
                'nombre' => 'Decoloración',
                'descripcion' => 'Decoloración completa de cabello.',
                'precio_base' => 400.00,
                'duracion_minutos' => 120,
                'activo' => true,
            ],
            [
                'nombre' => 'Afeitado Tradicional',
                'descripcion' => 'Afeitado completo con navaja tradicional, toallas calientes y aceites.',
                'precio_base' => 150.00,
                'duracion_minutos' => 45,
                'activo' => true,
            ],
            [
                'nombre' => 'Diseño en Cabello',
                'descripcion' => 'Diseño personalizado en cabello o barba.',
                'precio_base' => 100.00,
                'duracion_minutos' => 30,
                'activo' => true,
            ],
            [
                'nombre' => 'Permanente',
                'descripcion' => 'Permanente completa para rizado de cabello.',
                'precio_base' => 500.00,
                'duracion_minutos' => 150,
                'activo' => false, // Ejemplo de servicio inactivo
            ],
        ];

        foreach ($servicios as $servicio) {
            Servicio::create($servicio);
        }
    }
}
