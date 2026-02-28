@extends('layouts.app')
@section('title', 'Gestión de Citas')

@section('styles')
<style>
    /* Eliminar el hover molesto de las cards */
    .card {
        transition: none !important;
    }
    
    .card:hover {
        transform: none !important;
        box-shadow: 0 8px 24px rgba(0,0,0,0.08) !important;
    }

    /* Mejorar performance de modales */
    .modal {
        will-change: auto;
    }

    /* Evitar parpadeo en tabla */
    .table {
        backface-visibility: hidden;
        transform: translateZ(0);
    }
</style>
@endsection

@section('content')
<h1><i class="bi bi-clipboard-check"></i> Gestión de Citas</h1>

<div class="card mt-4" style="transition: none;">
    <div class="card-body">

        {{-- Alertas dentro de la card --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Filtros --}}
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

        {{-- Tabla --}}
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Servicio</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($citas as $cita)
                    <tr>
                        <td>{{ $cita->user->nombre_completo }}</td>
                        <td>{{ $cita->servicio->nombre }}</td>
                        <td>{{ $cita->fecha_formateada }} {{ $cita->hora_inicio_formateada }}</td>
                        <td><span class="badge bg-{{ $cita->estado_badge }}">{{ ucfirst($cita->estado) }}</span></td>
                        <td>
                            @if($cita->isPendiente())
                                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#aprobar{{ $cita->id }}">
                                    <i class="bi bi-check-circle"></i> Aprobar
                                </button>
                                <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rechazar{{ $cita->id }}">
                                    <i class="bi bi-x-circle"></i> Rechazar
                                </button>
                            @else
                                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#ver{{ $cita->id }}">
                                    <i class="bi bi-eye"></i> Ver
                                </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $citas->links() }}
    </div>
</div>

{{-- MODALES FUERA DEL FOREACH PARA MEJOR PERFORMANCE --}}
@foreach($citas as $cita)

{{-- Modal Aprobar --}}
<div class="modal fade" id="aprobar{{ $cita->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('admin.citas.aprobar', $cita) }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-check-circle"></i> Aprobar Cita
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    {{-- Información de la cita --}}
                    <div class="alert alert-info mb-3">
                        <h6 class="alert-heading">
                            <i class="bi bi-info-circle"></i> Información de la Cita
                        </h6>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong><i class="bi bi-person"></i> Cliente:</strong></p>
                                <p class="ms-3">{{ $cita->user->nombre_completo }}</p>
                                
                                <p class="mb-1"><strong><i class="bi bi-telephone"></i> Teléfono:</strong></p>
                                <p class="ms-3">{{ $cita->user->telefono }}</p>
                                
                                <p class="mb-1"><strong><i class="bi bi-envelope"></i> Email:</strong></p>
                                <p class="ms-3">{{ $cita->user->email }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong><i class="bi bi-scissors"></i> Servicio:</strong></p>
                                <p class="ms-3">{{ $cita->servicio->nombre }}</p>
                                
                                <p class="mb-1"><strong><i class="bi bi-calendar3"></i> Fecha:</strong></p>
                                <p class="ms-3">{{ $cita->fecha_formateada }}</p>
                                
                                <p class="mb-1"><strong><i class="bi bi-clock"></i> Horario:</strong></p>
                                <p class="ms-3">{{ $cita->hora_inicio_formateada }} - {{ $cita->hora_fin_formateada }}</p>
                                
                                <p class="mb-1"><strong><i class="bi bi-hourglass"></i> Duración:</strong></p>
                                <p class="ms-3">{{ $cita->servicio->duracion_minutos }} minutos</p>
                            </div>
                        </div>
                    </div>
                
                    {{-- Precio Final --}}
                    <div class="mb-3">
                        <label for="precio_final_{{ $cita->id }}" class="form-label fw-bold">
                            <i class="bi bi-currency-dollar"></i> Precio Final *
                        </label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-success text-white">
                                <i class="bi bi-cash-stack"></i>
                            </span>
                            <span class="input-group-text">$</span>
                            <input 
                                type="number" 
                                name="precio_final" 
                                id="precio_final_{{ $cita->id }}"
                                class="form-control form-control-lg" 
                                step="0.01" 
                                min="0"
                                value="{{ old('precio_final', $cita->precio_final ?? $cita->servicio->precio_base) }}"
                                required
                                style="font-size: 1.5rem; font-weight: bold;">
                            <span class="input-group-text">MXN</span>
                        </div>
                        <div class="form-text">
                            <i class="bi bi-info-circle"></i> Precio base del servicio: 
                            <strong class="text-success">${{ number_format($cita->servicio->precio_base, 2) }}</strong>
                            (puedes modificarlo si es necesario)
                        </div>
                    </div>
                
                    {{-- Nota del Admin --}}
                    <div class="mb-3">
                        <label for="nota_admin_{{ $cita->id }}" class="form-label fw-bold">
                            <i class="bi bi-chat-left-text"></i> Nota para el Cliente (opcional)
                        </label>
                        <textarea 
                            name="nota_admin" 
                            id="nota_admin_{{ $cita->id }}"
                            class="form-control" 
                            rows="3" 
                            placeholder="Ej: Cita confirmada. Te esperamos puntual.">{{ old('nota_admin', $cita->nota_admin) }}</textarea>
                        <small class="text-muted">Esta nota será visible para el cliente.</small>
                    </div>
                
                    {{-- Nota del Cliente (si existe) --}}
                    @if($cita->nota_cliente)
                        <div class="alert alert-warning">
                            <h6 class="alert-heading">
                                <i class="bi bi-chat-quote"></i> Nota del Cliente:
                            </h6>
                            <hr>
                            <p class="mb-0">{{ $cita->nota_cliente }}</p>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="bi bi-check-circle"></i> Confirmar y Aprobar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal Rechazar --}}
<div class="modal fade" id="rechazar{{ $cita->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('admin.citas.rechazar', $cita) }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-x-circle"></i> Rechazar Cita
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    {{-- Advertencia --}}
                    <div class="alert alert-warning mb-3">
                        <h6 class="alert-heading">
                            <i class="bi bi-exclamation-triangle"></i> ¿Estás seguro?
                        </h6>
                        <hr>
                        <p class="mb-0">El cliente recibirá una notificación del rechazo.</p>
                    </div>

                    {{-- Información de la cita --}}
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6 class="card-title">
                                <i class="bi bi-info-circle"></i> Detalles de la Cita
                            </h6>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-1"><strong><i class="bi bi-person"></i> Cliente:</strong></p>
                                    <p class="ms-3">{{ $cita->user->nombre_completo }}</p>
                                    
                                    <p class="mb-1"><strong><i class="bi bi-telephone"></i> Teléfono:</strong></p>
                                    <p class="ms-3">{{ $cita->user->telefono }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-1"><strong><i class="bi bi-scissors"></i> Servicio:</strong></p>
                                    <p class="ms-3">{{ $cita->servicio->nombre }}</p>
                                    
                                    <p class="mb-1"><strong><i class="bi bi-calendar-x"></i> Fecha:</strong></p>
                                    <p class="ms-3">{{ $cita->fecha_formateada }} - {{ $cita->hora_inicio_formateada }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                
                    {{-- Nota del Cliente (si existe) --}}
                    @if($cita->nota_cliente)
                        <div class="alert alert-info mb-3">
                            <h6 class="alert-heading">
                                <i class="bi bi-chat-quote"></i> Nota del Cliente:
                            </h6>
                            <hr>
                            <p class="mb-0">{{ $cita->nota_cliente }}</p>
                        </div>
                    @endif
                    
                    {{-- Razón del rechazo --}}
                    <div class="mb-3">
                        <label for="nota_rechazo_{{ $cita->id }}" class="form-label fw-bold text-danger">
                            <i class="bi bi-chat-left-text"></i> Razón del Rechazo *
                        </label>
                        <textarea 
                            name="nota_admin" 
                            id="nota_rechazo_{{ $cita->id }}"
                            class="form-control" 
                            rows="4" 
                            placeholder="Ej: Lo sentimos, no tenemos disponibilidad en ese horario..."
                            required>{{ old('nota_admin', $cita->nota_admin) }}</textarea>
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> Explica la razón y ofrece alternativas.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-arrow-left"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger btn-lg">
                        <i class="bi bi-x-circle"></i> Confirmar Rechazo
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Modal Ver Detalles --}}
<div class="modal fade" id="ver{{ $cita->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="bi bi-eye"></i> Detalles de la Cita
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="bi bi-person-badge"></i> Cliente</h6>
                        <hr>
                        <p><strong>Nombre:</strong> {{ $cita->user->nombre_completo }}</p>
                        <p><strong>Teléfono:</strong> {{ $cita->user->telefono }}</p>
                        <p><strong>Email:</strong> {{ $cita->user->email }}</p>
                        <p><strong>Tipo:</strong> 
                            <span class="badge bg-{{ $cita->user->isPermanente() ? 'success' : 'warning' }}">
                                {{ $cita->user->isPermanente() ? 'Permanente' : 'Temporal' }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="bi bi-calendar-check"></i> Cita</h6>
                        <hr>
                        <p><strong>Servicio:</strong> {{ $cita->servicio->nombre }}</p>
                        <p><strong>Fecha:</strong> {{ $cita->fecha_formateada }}</p>
                        <p><strong>Horario:</strong> {{ $cita->hora_inicio_formateada }} - {{ $cita->hora_fin_formateada }}</p>
                        <p><strong>Duración:</strong> {{ $cita->servicio->duracion_minutos }} min</p>
                        <p><strong>Estado:</strong> 
                            <span class="badge bg-{{ $cita->estado_badge }}">{{ ucfirst($cita->estado) }}</span>
                        </p>
                    </div>
                </div>

                @if($cita->precio_final)
                <hr>
                <div class="alert alert-success">
                    <h6><i class="bi bi-cash-stack"></i> Precio Final: <strong>${{ number_format($cita->precio_final, 2) }}</strong></h6>
                </div>
                @endif

                @if($cita->nota_cliente)
                <hr>
                <div class="alert alert-warning">
                    <h6><i class="bi bi-chat-quote"></i> Nota del Cliente:</h6>
                    <p class="mb-0">{{ $cita->nota_cliente }}</p>
                </div>
                @endif

                @if($cita->nota_admin)
                <hr>
                <div class="alert alert-info">
                    <h6><i class="bi bi-chat-text"></i> Nota del Admin:</h6>
                    <p class="mb-0">{{ $cita->nota_admin }}</p>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>

@endforeach
@endsection