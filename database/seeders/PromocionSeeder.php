<?php

namespace Database\Seeders;

use App\Models\Promocion;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PromocionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Promocion::create([
            'nombre' => 'Descuento Clientes Permanentes',
            'descripcion' => '10% de descuento para clientes permanentes',
            'descuento_porcentaje' => 10.00,
            'descuento_fijo' => null,
            'fecha_inicio' => Carbon::now()->subDays(30),
            'fecha_fin' => Carbon::now()->addDays(365),
            'solo_permanentes' => true,
            'activo' => true,
        ]);

        Promocion::create([
            'nombre' => 'Martes y Miércoles',
            'descripcion' => '$50 pesos de descuento en cortes los martes y miércoles',
            'descuento_porcentaje' => null,
            'descuento_fijo' => 50.00,
            'fecha_inicio' => Carbon::now(),
            'fecha_fin' => Carbon::now()->addMonths(6),
            'solo_permanentes' => false,
            'activo' => true,
        ]);
    }
}
