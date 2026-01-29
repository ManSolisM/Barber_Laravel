@extends('layouts.app')
@section('title', 'Gestión de Citas')
@section('content')
<h1><i class="bi bi-clipboard-check"></i> Gestión de Citas</h1>
<div class="card mt-4">
    <div class="card-body">
        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <select name="estado" class="form-select">
                    <option value="">Todos los estados</option>
                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="aceptada" {{ request('estado') == 'aceptada' ? 'selected' : '' }}>Aceptada</option>
                    <option value="rechazada" {{ request('estado') == 'rechazada' ? 'selected' : '' }}>Rechazada</option>
                </select>
            </div>
            <div class="col-md-4">
                <input type="date" name="fecha" class="form-control" value="{{ request('fecha') }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Filtrar</button>
            </div>
        </form>
        <table class="table">
            <thead><tr><th>Cliente</th><th>Servicio</th><th>Fecha</th><th>Estado</th><th>Acciones</th></tr></thead>
            <tbody>
                @foreach($citas as $cita)
                <tr>
                    <td>{{ $cita->user->nombre_completo }}</td>
                    <td>{{ $cita->servicio->nombre }}</td>
                    <td>{{ $cita->fecha_formateada }} {{ $cita->hora_inicio_formateada }}</td>
                    <td><span class="badge bg-{{ $cita->estado_badge }}">{{ ucfirst($cita->estado) }}</span></td>
                    <td>
                        @if($cita->isPendiente())
                        <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#aprobar{{ $cita->id }}">Aprobar</button>
                        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rechazar{{ $cita->id }}">Rechazar</button>
                        <!-- Modales aquí -->
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        {{ $citas->links() }}
    </div>
</div>
@endsection
