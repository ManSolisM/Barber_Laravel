@extends('layouts.app')
@section('title', 'Mis Citas')
@section('content')
<h1><i class="bi bi-list-check"></i> Mis Citas</h1>
<div class="card mt-4">
    <div class="card-body">
        <table class="table">
            <thead><tr><th>Servicio</th><th>Fecha</th><th>Hora</th><th>Estado</th><th>Precio</th><th>Acciones</th></tr></thead>
            <tbody>
                @forelse($citas as $cita)
                <tr>
                    <td>{{ $cita->servicio->nombre }}</td>
                    <td>{{ $cita->fecha_formateada }}</td>
                    <td>{{ $cita->hora_inicio_formateada }}</td>
                    <td><span class="badge bg-{{ $cita->estado_badge }}">{{ ucfirst($cita->estado) }}</span></td>
                    <td>{{ $cita->precio_mostrar }}</td>
                    <td>
                        @if(in_array($cita->estado, ['pendiente', 'aceptada']))
                        <form method="POST" action="{{ route('cliente.citas.cancelar', $cita) }}" onsubmit="return confirm('¿Cancelar esta cita?')">
                            @csrf
                            <button class="btn btn-sm btn-danger">Cancelar</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">No tienes citas</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $citas->links() }}
    </div>
</div>
@endsection
