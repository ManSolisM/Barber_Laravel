<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Servicio;
use App\Http\Requests\StoreCitaRequest;
use App\Notifications\CitaCreada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Carbon\Carbon;

class CitaController extends Controller
{
    use AuthorizesRequests;
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
        // Verificar si el usuario puede agendar más citas
        $user = Auth::user();
        
        if (!$user->puedeAgendarCita()) {
            return redirect()
                ->route('cliente.dashboard')
                ->withErrors(['error' => 'Tienes el límite máximo de citas pendientes (3). Espera a que sean aprobadas o canceladas.']);
        }

        $servicios = Servicio::activos()->get();
        return view('cliente.crear-cita', compact('servicios'));
    }

    /**
     * Guardar nueva cita
     */
    public function guardar(StoreCitaRequest $request)
    {
        try {
            $validated = $request->validated();
            
            $servicio = Servicio::findOrFail($validated['servicio_id']);
            
            // Verificar que el servicio esté activo
            if (!$servicio->activo) {
                return back()->withErrors(['servicio_id' => 'El servicio seleccionado no está disponible.'])->withInput();
            }
            
            // Calcular hora fin
            $horaInicio = Carbon::parse($validated['hora_inicio']);
            $horaFin = $horaInicio->copy()->addMinutes($servicio->duracion_minutos);

            // Verificar que no termine después de las 19:00
            if ($horaFin->hour >= 19 || ($horaFin->hour == 19 && $horaFin->minute > 0)) {
                return back()->withErrors(['hora_inicio' => 'La cita terminaría después del horario de atención (19:00).'])->withInput();
            }

            // Verificar disponibilidad con bloqueo optimista
            $citaExistente = Cita::where('fecha', $validated['fecha'])
                ->where(function ($query) use ($horaInicio, $horaFin) {
                    $query->where(function ($q) use ($horaInicio, $horaFin) {
                        $q->where('hora_inicio', '<', $horaFin->format('H:i'))
                          ->where('hora_fin', '>', $horaInicio->format('H:i'));
                    });
                })
                ->whereNotIn('estado', ['cancelada', 'rechazada'])
                ->exists();

            if ($citaExistente) {
                return back()->withErrors(['hora_inicio' => 'El horario seleccionado no está disponible. Por favor elige otro horario.'])->withInput();
            }

            // Crear la cita
            $cita = Cita::create([
                'user_id' => auth()->id(),
                'servicio_id' => $validated['servicio_id'],
                'fecha' => $validated['fecha'],
                'hora_inicio' => $horaInicio->format('H:i'),
                'hora_fin' => $horaFin->format('H:i'),
                'precio_estimado' => $servicio->precio_base,
                'estado' => 'pendiente',
                'nota_cliente' => $validated['nota_cliente'] ?? null,
            ]);

            // Cargar relaciones para la notificación
            $cita->load('servicio');

            // Notificar al usuario
            auth()->user()->notify(new CitaCreada($cita));

            return redirect()
                ->route('cliente.mis-citas')
                ->with('success', '¡Cita agendada exitosamente! Te hemos enviado un email de confirmación. Espera la aprobación del administrador.');

        } catch (\Exception $e) {
            Log::error('Error al crear cita: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'data' => $request->all()
            ]);
            
            return back()->withErrors(['error' => 'Ocurrió un error al agendar la cita. Por favor intenta nuevamente.'])->withInput();
        }
    }

    /**
     * Cancelar una cita
     */
    public function cancelar(Cita $cita)
    {
        // Autorizar usando policy
        $this->authorize('cancelar', $cita);

        try {
            // Verificar que la cita se pueda cancelar (más de 2 horas de anticipación)
            if (!$cita->sePuedeCancelar()) {
                return back()->withErrors(['error' => 'Solo puedes cancelar citas con al menos 2 horas de anticipación.']);
            }

            $cita->update(['estado' => 'cancelada']);
            
            Log::info('Cita cancelada', [
                'cita_id' => $cita->id,
                'user_id' => auth()->id()
            ]);

            return back()->with('success', 'Cita cancelada exitosamente.');

        } catch (\Exception $e) {
            Log::error('Error al cancelar cita: ' . $e->getMessage(), [
                'cita_id' => $cita->id,
                'user_id' => auth()->id()
            ]);
            
            return back()->withErrors(['error' => 'No se pudo cancelar la cita. Por favor intenta nuevamente.']);
        }
    }

    /**
     * Ver historial de citas (solo clientes permanentes)
     */
    public function historial()
    {
        $user = Auth::user();
        
        $citas = Cita::where('user_id', $user->id)
            ->with('servicio')
            ->pasadas()
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_inicio', 'desc')
            ->paginate(20);

        $totalGastado = Cita::where('user_id', $user->id)
            ->where('estado', 'aceptada')
            ->pasadas()
            ->sum('precio_final');

        $totalCitas = Cita::where('user_id', $user->id)
            ->where('estado', 'aceptada')
            ->pasadas()
            ->count();

        return view('cliente.historial', compact('citas', 'totalGastado', 'totalCitas'));
    }

    /**
     * Verificar disponibilidad de horario (API para AJAX)
     */
    public function verificarDisponibilidadApi(Request $request)
    {
        try {
            $request->validate([
                'fecha' => 'required|date|after_or_equal:today',
                'hora_inicio' => 'required|date_format:H:i',
                'servicio_id' => 'required|exists:servicios,id',
            ]);

            // Verificar que no sea domingo
            $fecha = Carbon::parse($request->fecha);
            if ($fecha->dayOfWeek == 0) {
                return response()->json([
                    'disponible' => false,
                    'mensaje' => 'No se aceptan citas los domingos'
                ]);
            }

            $servicio = Servicio::find($request->servicio_id);
            
            // Verificar que el servicio esté activo
            if (!$servicio || !$servicio->activo) {
                return response()->json([
                    'disponible' => false,
                    'mensaje' => 'El servicio no está disponible'
                ]);
            }

            $horaInicio = Carbon::parse($request->hora_inicio);
            $horaFin = $horaInicio->copy()->addMinutes($servicio->duracion_minutos);

            // Verificar horario de negocio (9:00 - 19:00)
            if ($horaInicio->hour < 9 || $horaInicio->hour >= 19) {
                return response()->json([
                    'disponible' => false,
                    'mensaje' => 'Fuera del horario de atención (9:00 - 19:00)'
                ]);
            }

            // Verificar que no termine después de las 19:00
            if ($horaFin->hour > 19 || ($horaFin->hour == 19 && $horaFin->minute > 0)) {
                return response()->json([
                    'disponible' => false,
                    'mensaje' => 'La cita terminaría después del horario de atención (19:00)'
                ]);
            }

            // Verificar disponibilidad en la base de datos
            $citaExistente = Cita::where('fecha', $request->fecha)
                ->where(function ($query) use ($horaInicio, $horaFin) {
                    $query->where(function ($q) use ($horaInicio, $horaFin) {
                        $q->where('hora_inicio', '<', $horaFin->format('H:i'))
                          ->where('hora_fin', '>', $horaInicio->format('H:i'));
                    });
                })
                ->whereNotIn('estado', ['cancelada', 'rechazada'])
                ->exists();

            if ($citaExistente) {
                return response()->json([
                    'disponible' => false,
                    'mensaje' => 'Horario no disponible - Ya existe una cita en ese rango'
                ]);
            }

            // Todo OK
            return response()->json([
                'disponible' => true,
                'mensaje' => '✓ Horario disponible',
                'hora_fin' => $horaFin->format('H:i'),
                'duracion' => $servicio->duracion_formateada
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'disponible' => false,
                'mensaje' => 'Datos inválidos'
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Error en verificarDisponibilidadApi: ' . $e->getMessage());
            
            return response()->json([
                'disponible' => false,
                'mensaje' => 'Error al verificar disponibilidad'
            ], 500);
        }
    }
}