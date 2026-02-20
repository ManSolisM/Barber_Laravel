@extends('layouts.app')

@section('title', 'Historial de Citas')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-clock-history"></i> Historial de Citas</h1>
    </div>

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-calendar-check"></i> Total de Citas</h5>
                    <h2 class="mb-0">{{ $totalCitas }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title"><i class="bi bi-currency-dollar"></i> Total Gastado</h5>
                    <h2 class="mb-0">${{ number_format($totalGastado, 2) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Listado de citas pasadas -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Citas Anteriores</h5>
        </div>
        <div class="card-body">
            @if($citas->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Servicio</th>
                                <th>Estado</th>
                                <th>Precio</th>
                                <th>Nota Admin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($citas as $cita)
                            <tr>
                                <td>
                                    <i class="bi bi-calendar3"></i> 
                                    {{ $cita->fecha_formateada }}
                                </td>
                                <td>
                                    <i class="bi bi-clock"></i>
                                    {{ $cita->hora_inicio_formateada }}
                                </td>
                                <td>
                                    <strong>{{ $cita->servicio->nombre }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $cita->estado_badge }}">
                                        {{ ucfirst($cita->estado) }}
                                    </span>
                                </td>
                                <td>
                                    <strong>{{ $cita->precio_mostrar }}</strong>
                                </td>
                                <td>
                                    @if($cita->nota_admin)
                                        <small class="text-muted">{{ $cita->nota_admin }}</small>
                                    @else
                                        <small class="text-muted">-</small>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="mt-3">
                    {{ $citas->links() }}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> No tienes citas en tu historial aún.
                </div>
            @endif
        </div>
    </div>

    <!-- Botón volver -->
    <div class="mt-3">
        <a href="{{ route('cliente.dashboard') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver al Dashboard
        </a>
    </div>
</div>
@endsection