@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1><i class="bi bi-speedometer2"></i> Dashboard Administrador</h1>
        <p class="text-muted">Panel de control y estadísticas</p>
    </div>
</div>

<!-- Estadísticas -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body text-center">
                <i class="bi bi-clipboard-check" style="font-size: 3rem;"></i>
                <h3 class="mt-3">{{ $stats['total_citas'] }}</h3>
                <p class="mb-0">Total Citas</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card warning">
            <div class="card-body text-center">
                <i class="bi bi-hourglass-split" style="font-size: 3rem;"></i>
                <h3 class="mt-3">{{ $stats['citas_pendientes'] }}</h3>
                <p class="mb-0">Citas Pendientes</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card success">
            <div class="card-body text-center">
                <i class="bi bi-calendar-check" style="font-size: 3rem;"></i>
                <h3 class="mt-3">{{ $stats['citas_hoy'] }}</h3>
                <p class="mb-0">Citas Hoy</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card info">
            <div class="card-body text-center">
                <i class="bi bi-people" style="font-size: 3rem;"></i>
                <h3 class="mt-3">{{ $stats['total_clientes'] }}</h3>
                <p class="mb-0">Total Clientes</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card success">
            <div class="card-body text-center">
                <i class="bi bi-star" style="font-size: 3rem;"></i>
                <h3 class="mt-3">{{ $stats['clientes_permanentes'] }}</h3>
                <p class="mb-0">Clientes Permanentes</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
            <div class="card-body text-center">
                <i class="bi bi-cash-stack" style="font-size: 3rem;"></i>
                <h3 class="mt-3">${{ number_format($stats['ingresos_mes'], 2) }}</h3>
                <p class="mb-0">Ingresos del Mes</p>
            </div>
        </div>
    </div>
</div>

<!-- Citas Pendientes -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="bi bi-exclamation-circle"></i> Citas Pendientes de Aprobación</h5>
            </div>
            <div class="card-body">
                @if($citasPendientes->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Servicio</th>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Precio Est.</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($citasPendientes as $cita)
                                    <tr>
                                        <td>
                                            {{ $cita->user->nombre_completo }}
                                            <br><small class="text-muted">{{ $cita->user->telefono }}</small>
                                        </td>
                                        <td>{{ $cita->servicio->nombre }}</td>
                                        <td>{{ $cita->fecha_formateada }}</td>
                                        <td>{{ $cita->hora_inicio_formateada }} - {{ $cita->hora_fin_formateada }}</td>
                                        <td>${{ number_format($cita->precio_estimado, 2) }}</td>
                                        <td>
                                            <a href="{{ route('admin.citas') }}?estado=pendiente" class="btn btn-sm btn-primary">
                                                <i class="bi bi-eye"></i> Revisar
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center mb-0">No hay citas pendientes</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Próximas Citas -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-calendar-event"></i> Próximas Citas Confirmadas</h5>
            </div>
            <div class="card-body">
                @if($proximasCitas->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Cliente</th>
                                    <th>Servicio</th>
                                    <th>Fecha</th>
                                    <th>Hora</th>
                                    <th>Precio</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($proximasCitas as $cita)
                                    <tr>
                                        <td>
                                            {{ $cita->user->nombre_completo }}
                                            <br><small class="text-muted">{{ $cita->user->telefono }}</small>
                                        </td>
                                        <td>{{ $cita->servicio->nombre }}</td>
                                        <td>{{ $cita->fecha_formateada }}</td>
                                        <td>{{ $cita->hora_inicio_formateada }} - {{ $cita->hora_fin_formateada }}</td>
                                        <td>
                                            @if($cita->precio_final)
                                                ${{ number_format($cita->precio_final, 2) }}
                                            @else
                                                <span class="text-muted">Por definir</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $cita->estado_badge }}">
                                                {{ ucfirst($cita->estado) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center mb-0">No hay próximas citas confirmadas</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
