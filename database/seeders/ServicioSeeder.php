<?php

namespace Database\Seeders;

use App\Models\Servicio;
use Illuminate\Database\Seeder;

class ServicioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $servicios = [
            [
                'nombre' => 'Corte Clásico',
                'descripcion' => 'Corte tradicional con máquina y tijera',
                'precio_base' => 150.00,
                'duracion_minutos' => 30,
                'activo' => true,
            ],
            [
                'nombre' => 'Corte + Barba',
                'descripcion' => 'Corte de cabello más arreglo de barba',
                'precio_base' => 250.00,
                'duracion_minutos' => 45,
                'activo' => true,
            ],
            [
                'nombre' => 'Barba',
                'descripcion' => 'Arreglo y perfilado de barba',
                'precio_base' => 120.00,
                'duracion_minutos' => 20,
                'activo' => true,
            ],
            [
                'nombre' => 'Fade Degradado',
                'descripcion' => 'Corte con degradado profesional',
                'precio_base' => 200.00,
                'duracion_minutos' => 40,
                'activo' => true,
            ],
            [
                'nombre' => 'Rapado',
                'descripcion' => 'Corte completo con máquina',
                'precio_base' => 100.00,
                'duracion_minutos' => 15,
                'activo' => true,
            ],
            [
                'nombre' => 'Corte Niño',
                'descripcion' => 'Corte especial para niños',
                'precio_base' => 120.00,
                'duracion_minutos' => 25,
                'activo' => true,
            ],
            [
                'nombre' => 'Diseño Artístico',
                'descripcion' => 'Diseños y figuras en el cabello',
                'precio_base' => 300.00,
                'duracion_minutos' => 60,
                'activo' => true,
            ],
            [
                'nombre' => 'Cejas',
                'descripcion' => 'Perfilado y arreglo de cejas',
                'precio_base' => 80.00,
                'duracion_minutos' => 10,
                'activo' => true,
            ],
        ];

        foreach ($servicios as $servicio) {
            Servicio::create($servicio);
        }
    }
}
