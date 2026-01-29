@extends('layouts.app')
@section('title', 'Mi Dashboard')
@section('content')
<h1><i class="bi bi-house"></i> Bienvenido, {{ $user->nombre }}</h1>

@if($user->isTemporal())
<div class="alert alert-info">
    <i class="bi bi-info-circle"></i> Eres un cliente <strong>temporal</strong>. 
    Todas tus citas requieren aprobación del administrador.
</div>
@else
<div class="alert alert-success">
    <i class="bi bi-star"></i> ¡Eres un cliente <strong>permanente</strong>! 
    Disfruta de beneficios exclusivos.
</div>
@endif

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card stat-card warning">
            <div class="card-body text-center">
                <h3>{{ $citasPendientes }}</h3>
                <p>Citas Pendientes</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card success">
            <div class="card-body text-center">
                <h3>{{ $citasAceptadas }}</h3>
                <p>Citas Confirmadas</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card info">
            <div class="card-body text-center">
                <h3>{{ $misCitas->count() }}</h3>
                <p>Total Citas</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Mis Últimas Citas</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Servicio</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Estado</th>
                        <th>Precio</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($misCitas as $cita)
                    <tr>
                        <td>{{ $cita->servicio->nombre }}</td>
                        <td>{{ $cita->fecha_formateada }}</td>
                        <td>{{ $cita->hora_inicio_formateada }}</td>
                        <td>
                            <span class="badge bg-{{ $cita->estado_badge }}">
                                {{ ucfirst($cita->estado) }}
                            </span>
                        </td>
                        <td>{{ $cita->precio_mostrar }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            No tienes citas aún
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="text-center mt-3">
            <a href="{{ route('cliente.citas.crear') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-plus-circle"></i> Agendar Nueva Cita
            </a>
        </div>
    </div>
</div>
@endsection
