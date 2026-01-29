<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CitaController extends Controller
{
    /**
     * Dashboard del cliente
     */
    public function dashboard()
    {
        $user = Auth::user();
        
        $misCitas = Cita::where('user_id', $user->id)
            ->with('servicio')
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_inicio', 'desc')
            ->take(10)
            ->get();

        $citasPendientes = $misCitas->where('estado', 'pendiente')->count();
        $citasAceptadas = $misCitas->where('estado', 'aceptada')->count();

        return view('cliente.dashboard', compact('misCitas', 'citasPendientes', 'citasAceptadas', 'user'));
    }

    /**
     * Ver mis citas
     */
    public function misCitas()
    {
        $user = Auth::user();
        
        $citas = Cita::where('user_id', $user->id)
            ->with('servicio')
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_inicio', 'desc')
            ->paginate(15);

        return view('cliente.mis-citas', compact('citas'));
    }

    /**
     * Mostrar formulario para crear cita
     */
    public function crear()
    {
        $servicios = Servicio::activos()->get();
        return view('cliente.crear-cita', compact('servicios'));
    }

    /**
     * Guardar nueva cita
     */
    public function guardar(Request $request)
    {
        $validated = $request->validate([
            'servicio_id' => 'required|exists:servicios,id',
            'fecha' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required|date_format:H:i',
            'nota_cliente' => 'nullable|string|max:500',
        ]);

        $servicio = Servicio::findOrFail($validated['servicio_id']);
        
        // Calcular hora fin
        $horaInicio = Carbon::parse($validated['hora_inicio']);
        $horaFin = $horaInicio->copy()->addMinutes($servicio->duracion_minutos);

        // Verificar disponibilidad
        $citaExistente = Cita::where('fecha', $validated['fecha'])
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

        if ($citaExistente) {
            return back()->withErrors(['hora_inicio' => 'Este horario ya está ocupado.'])->withInput();
        }

        Cita::create([
            'user_id' => Auth::id(),
            'servicio_id' => $servicio->id,
            'fecha' => $validated['fecha'],
            'hora_inicio' => $horaInicio->format('H:i'),
            'hora_fin' => $horaFin->format('H:i'),
            'precio_estimado' => $servicio->precio_base,
            'nota_cliente' => $validated['nota_cliente'] ?? null,
            'estado' => 'pendiente',
        ]);

        return redirect()->route('cliente.mis-citas')->with('success', 'Cita creada. Espera la aprobación del administrador.');
    }

    /**
     * Cancelar cita
     */
    public function cancelar(Cita $cita)
    {
        // Verificar que la cita pertenece al usuario
        if ($cita->user_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }

        // Solo se pueden cancelar citas pendientes o aceptadas
        if (!in_array($cita->estado, ['pendiente', 'aceptada'])) {
            return back()->with('error', 'Esta cita no puede ser cancelada.');
        }

        $cita->update(['estado' => 'cancelada']);

        return back()->with('success', 'Cita cancelada exitosamente.');
    }

    /**
     * Ver historial (solo clientes permanentes)
     */
    public function historial()
    {
        $user = Auth::user();

        if (!$user->isPermanente()) {
            abort(403, 'Solo clientes permanentes pueden ver el historial.');
        }

        $historial = Cita::where('user_id', $user->id)
            ->with('servicio')
            ->pasadas()
            ->where('estado', 'aceptada')
            ->orderBy('fecha', 'desc')
            ->paginate(20);

        return view('cliente.historial', compact('historial'));
    }
}
