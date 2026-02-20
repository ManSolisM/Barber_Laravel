<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cita;
use App\Models\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    
    /**
     * Dashboard del admin
     */
    public function dashboard()
    {
        $stats = [
            'total_citas' => Cita::count(),
            'citas_pendientes' => Cita::pendientes()->count(),
            'citas_hoy' => Cita::whereDate('fecha', today())->count(),
            'total_clientes' => User::clientes()->count(),
            'clientes_permanentes' => User::permanentes()->count(),
            'ingresos_mes' => Cita::aceptadas()
                ->whereMonth('fecha', now()->month)
                ->sum('precio_final'),
        ];

        $citasPendientes = Cita::with(['user', 'servicio'])
            ->pendientes()
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->take(10)
            ->get();

        $proximasCitas = Cita::with(['user', 'servicio'])
            ->aceptadas()
            ->futuras()
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'citasPendientes', 'proximasCitas'));
    }

    /**
     * Gestión de clientes
     */
    public function clientes()
    {
        $clientes = User::clientes()
            ->withCount('citas')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.clientes', compact('clientes'));
    }

    /**
     * Aprobar cliente (temporal -> permanente)
     */
    public function aprobarCliente(User $user)
    {
        if ($user->role !== 'cliente') {
            return back()->with('error', 'Solo se pueden aprobar clientes.');
        }

        $user->update(['tipo_cliente' => 'permanente']);

        return back()->with('success', 'Cliente aprobado como permanente.');
    }

    /**
     * Gestión de citas
     */
    public function citas(Request $request)
    {
        $query = Cita::with(['user', 'servicio']);

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->fecha);
        }

        $citas = $query->orderBy('fecha', 'desc')
                      ->orderBy('hora_inicio', 'desc')
                      ->paginate(20);

        return view('admin.citas', compact('citas'));
    }

    /**
     * Aprobar cita
     */
    public function aprobarCita(Request $request, Cita $cita)
    {
        $validated = $request->validate([
            'precio_final' => 'required|numeric|min:0',
            'nota_admin' => 'nullable|string',
        ]);

        $cita->update([
            'estado' => 'aceptada',
            'precio_final' => $validated['precio_final'],
            'nota_admin' => $validated['nota_admin'] ?? null,
        ]);

        // Aquí se podría enviar una notificación al cliente
        // Mail::to($cita->user->email)->send(new CitaAprobada($cita));

        return back()->with('success', 'Cita aprobada exitosamente.');
    }

    /**
     * Rechazar cita
     */
    public function rechazarCita(Request $request, Cita $cita)
    {
        $validated = $request->validate([
            'nota_admin' => 'required|string',
        ]);

        $cita->update([
            'estado' => 'rechazada',
            'nota_admin' => $validated['nota_admin'],
        ]);

        return back()->with('success', 'Cita rechazada.');
    }

    /**
     * Gestión de servicios
     */
    public function servicios()
    {
        $servicios = Servicio::withCount('citas')->get();
        return view('admin.servicios', compact('servicios'));
    }

    /**
     * Crear servicio
     */
    public function crearServicio(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio_base' => 'required|numeric|min:0',
            'duracion_minutos' => 'required|integer|min:1',
        ]);

        Servicio::create($validated);

        return back()->with('success', 'Servicio creado exitosamente.');
    }

    /**
     * Actualizar servicio
     */
    public function actualizarServicio(Request $request, Servicio $servicio)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio_base' => 'required|numeric|min:0',
            'duracion_minutos' => 'required|integer|min:1',
            'activo' => 'boolean',
        ]);

        $servicio->update($validated);

        return back()->with('success', 'Servicio actualizado exitosamente.');
    }

    /**
     * Toggle estado del servicio
     */
    public function toggleServicio(Servicio $servicio)
    {
        $servicio->update(['activo' => !$servicio->activo]);
        
        $estado = $servicio->activo ? 'activado' : 'desactivado';
        return back()->with('success', "Servicio {$estado} exitosamente.");
    }
}
