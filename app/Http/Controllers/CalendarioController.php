<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Servicio;
use Illuminate\Http\Request;

class CalendarioController extends Controller
{
    /**
     * Mostrar calendario público
     */
    public function index()
    {
        $servicios = Servicio::activos()->get();
        return view('calendario.index', compact('servicios'));
    }

    /**
     * Obtener eventos para el calendario (API)
     */
    public function eventos(Request $request)
    {
        $citas = Cita::with('servicio')
            ->whereIn('estado', ['pendiente', 'aceptada'])
            ->when($request->filled('start'), function ($query) use ($request) {
                $query->where('fecha', '>=', $request->start);
            })
            ->when($request->filled('end'), function ($query) use ($request) {
                $query->where('fecha', '<=', $request->end);
            })
            ->get();

        $eventos = $citas->map(function ($cita) {
            $color = $cita->estado === 'aceptada' ? '#dc3545' : '#ffc107';
            
            return [
                'id' => $cita->id,
                'title' => 'OCUPADO',
                'start' => $cita->fecha->format('Y-m-d') . 'T' . $cita->hora_inicio_formateada,
                'end' => $cita->fecha->format('Y-m-d') . 'T' . $cita->hora_fin_formateada,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'textColor' => '#ffffff',
                'extendedProps' => [
                    'servicio' => $cita->servicio->nombre,
                    'estado' => $cita->estado,
                ],
            ];
        });

        return response()->json($eventos);
    }

    /**
     * Verificar disponibilidad de horario
     */
    public function verificarDisponibilidad(Request $request)
    {
        $validated = $request->validate([
            'fecha' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'servicio_id' => 'required|exists:servicios,id',
        ]);

        $servicio = Servicio::findOrFail($validated['servicio_id']);
        $horaInicio = \Carbon\Carbon::parse($validated['hora_inicio']);
        $horaFin = $horaInicio->copy()->addMinutes($servicio->duracion_minutos);

        $disponible = !Cita::where('fecha', $validated['fecha'])
            ->where(function ($query) use ($horaInicio, $horaFin) {
                $query->whereBetween('hora_inicio', [$horaInicio->format('H:i'), $horaFin->format('H:i')])
                      ->orWhereBetween('hora_fin', [$horaInicio->format('H:i'), $horaFin->format('H:i')])
                      ->orWhere(function ($q) use ($horaInicio, $horaFin) {
                          $q->where('hora_inicio', '<=', $horaInicio->format('H:i'))
                            ->where('hora_fin', '>=', $horaFin->format('H:i'));
                      });
            })
            ->whereIn('estado', ['pendiente', 'aceptada'])
            ->exists();

        return response()->json([
            'disponible' => $disponible,
            'hora_fin' => $horaFin->format('H:i'),
        ]);
    }
}
