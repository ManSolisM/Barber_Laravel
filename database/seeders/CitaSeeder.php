<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cita;
use App\Models\User;
use App\Models\Servicio;
use Carbon\Carbon;

class CitaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientes = User::clientes()->get();
        $servicios = Servicio::activos()->get();

        if ($clientes->isEmpty() || $servicios->isEmpty()) {
            return;
        }

        // Citas de hoy
        $hoy = Carbon::today();
        
        // Cita aceptada para hoy
        Cita::create([
            'user_id' => $clientes->random()->id,
            'servicio_id' => $servicios->random()->id,
            'fecha' => $hoy,
            'hora_inicio' => '10:00',
            'hora_fin' => '10:45',
            'precio_estimado' => 150.00,
            'precio_final' => 150.00,
            'estado' => 'aceptada',
            'nota_cliente' => 'Primera vez en la barbería',
            'nota_admin' => 'Cliente puntual',
        ]);

        // Cita aceptada para hoy (tarde)
        Cita::create([
            'user_id' => $clientes->random()->id,
            'servicio_id' => $servicios->random()->id,
            'fecha' => $hoy,
            'hora_inicio' => '15:00',
            'hora_fin' => '16:00',
            'precio_estimado' => 200.00,
            'precio_final' => 220.00,
            'estado' => 'aceptada',
            'nota_cliente' => null,
            'nota_admin' => 'Cliente habitual, se aplicó cargo extra por barba larga',
        ]);

        // Cita pendiente para hoy
        Cita::create([
            'user_id' => User::temporales()->first()->id,
            'servicio_id' => $servicios->first()->id,
            'fecha' => $hoy,
            'hora_inicio' => '17:00',
            'hora_fin' => '17:45',
            'precio_estimado' => 150.00,
            'estado' => 'pendiente',
            'nota_cliente' => 'Es mi primera vez, espero puedan atenderme',
        ]);

        // Citas para mañana
        $mañana = Carbon::tomorrow();
        
        Cita::create([
            'user_id' => $clientes->random()->id,
            'servicio_id' => $servicios->random()->id,
            'fecha' => $mañana,
            'hora_inicio' => '09:00',
            'hora_fin' => '09:45',
            'precio_estimado' => 150.00,
            'precio_final' => 150.00,
            'estado' => 'aceptada',
        ]);

        Cita::create([
            'user_id' => $clientes->random()->id,
            'servicio_id' => $servicios->random()->id,
            'fecha' => $mañana,
            'hora_inicio' => '11:00',
            'hora_fin' => '12:00',
            'precio_estimado' => 200.00,
            'estado' => 'pendiente',
            'nota_cliente' => 'Necesito corte y barba por favor',
        ]);

        // Citas para dentro de 3 días
        $tresDias = Carbon::today()->addDays(3);
        
        Cita::create([
            'user_id' => User::permanentes()->first()->id,
            'servicio_id' => $servicios->random()->id,
            'fecha' => $tresDias,
            'hora_inicio' => '14:00',
            'hora_fin' => '14:30',
            'precio_estimado' => 120.00,
            'precio_final' => 120.00,
            'estado' => 'aceptada',
        ]);

        // Citas pasadas (historial)
        $ayer = Carbon::yesterday();
        
        Cita::create([
            'user_id' => User::permanentes()->first()->id,
            'servicio_id' => $servicios->first()->id,
            'fecha' => $ayer,
            'hora_inicio' => '10:00',
            'hora_fin' => '10:45',
            'precio_estimado' => 150.00,
            'precio_final' => 150.00,
            'estado' => 'completada',
            'nota_admin' => 'Servicio completado exitosamente',
        ]);

        Cita::create([
            'user_id' => User::permanentes()->first()->id,
            'servicio_id' => $servicios->skip(1)->first()->id,
            'fecha' => Carbon::today()->subDays(7),
            'hora_inicio' => '15:00',
            'hora_fin' => '16:00',
            'precio_estimado' => 200.00,
            'precio_final' => 200.00,
            'estado' => 'completada',
        ]);

        // Cita rechazada (ejemplo)
        Cita::create([
            'user_id' => User::temporales()->first()->id,
            'servicio_id' => $servicios->first()->id,
            'fecha' => Carbon::today()->subDays(5),
            'hora_inicio' => '18:00',
            'hora_fin' => '18:45',
            'precio_estimado' => 150.00,
            'estado' => 'rechazada',
            'nota_admin' => 'Horario no disponible, por favor elige otro horario',
        ]);

        // Cita cancelada (ejemplo)
        Cita::create([
            'user_id' => $clientes->random()->id,
            'servicio_id' => $servicios->random()->id,
            'fecha' => Carbon::today()->subDays(3),
            'hora_inicio' => '12:00',
            'hora_fin' => '12:45',
            'precio_estimado' => 150.00,
            'estado' => 'cancelada',
            'nota_admin' => 'Cancelado por el cliente',
        ]);
    }
}
