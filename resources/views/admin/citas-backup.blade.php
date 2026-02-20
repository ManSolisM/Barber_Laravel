@extends('layouts.app')
@section('title', 'Gestión de Citas')
@section('content')
<h1><i class="bi bi-clipboard-check"></i> Gestión de Citas</h1>

<div class="card mt-4">
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
                            <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#aprobar{{ $cita->id }}">Aprobar</button>
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rechazar{{ $cita->id }}">Rechazar</button>
                        @endif
                    </td>
                </tr>

                {{-- Modal Aprobar --}}
                <div class="modal fade" id="aprobar{{ $cita->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
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
                                        <strong><i class="bi bi-info-circle"></i> Información de la Cita</strong>
                                        <ul class="mb-0 mt-2">
                                            <li><strong>Cliente:</strong> {{ $cita->user->nombre_completo }}</li>
                                            <li><strong>Servicio:</strong> {{ $cita->servicio->nombre }}</li>
                                            <li><strong>Fecha:</strong> {{ $cita->fecha_formateada }}</li>
                                            <li><strong>Hora:</strong> {{ $cita->hora_inicio_formateada }} - {{ $cita->hora_fin_formateada }}</li>
                                            <li><strong>Precio Base:</strong> ${{ number_format($cita->servicio->precio_base, 2) }}</li>
                                        </ul>
                                    </div>
                                
                                    {{-- Precio Final --}}
                                    <div class="mb-3">
                                        <label for="precio_final_{{ $cita->id }}" class="form-label">
                                            <i class="bi bi-currency-dollar"></i> Precio Final *
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">$</span>
                                            <input 
                                                type="number" 
                                                name="precio_final" 
                                                id="precio_final_{{ $cita->id }}"
                                                class="form-control" 
                                                step="0.01" 
                                                min="0"
                                                value="{{ $cita->precio_final ?? $cita->servicio->precio_base }}"
                                                required>
                                        </div>
                                        <small class="text-muted">
                                            Precio base del servicio: ${{ number_format($cita->servicio->precio_base, 2) }}
                                        </small>
                                    </div>
                                
                                    {{-- Nota del Admin --}}
                                    <div class="mb-3">
                                        <label for="nota_admin_{{ $cita->id }}" class="form-label">
                                            <i class="bi bi-chat-left-text"></i> Nota para el Cliente (opcional)
                                        </label>
                                        <textarea 
                                            name="nota_admin" 
                                            id="nota_admin_{{ $cita->id }}"
                                            class="form-control" 
                                            rows="3" 
                                            placeholder="Ej: Cita confirmada. Te esperamos puntual.">{{ $cita->nota_admin }}</textarea>
                                        <small class="text-muted">Esta nota será visible para el cliente.</small>
                                    </div>
                                
                                    {{-- Nota del Cliente (si existe) --}}
                                    @if($cita->nota_cliente)
                                        <div class="alert alert-warning">
                                            <strong><i class="bi bi-chat-quote"></i> Nota del Cliente:</strong>
                                            <p class="mb-0 mt-1">{{ $cita->nota_cliente }}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="bi bi-x-circle"></i> Cancelar
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check-circle"></i> Aprobar Cita
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Modal Rechazar --}}
                <div class="modal fade" id="rechazar{{ $cita->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
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
                                    {{-- Información de la cita --}}
                                    <div class="alert alert-warning mb-3">
                                        <strong><i class="bi bi-exclamation-triangle"></i> ¿Estás seguro?</strong>
                                        <p class="mb-0 mt-2">Vas a rechazar la siguiente cita:</p>
                                        <ul class="mb-0 mt-2">
                                            <li><strong>Cliente:</strong> {{ $cita->user->nombre_completo }}</li>
                                            <li><strong>Servicio:</strong> {{ $cita->servicio->nombre }}</li>
                                            <li><strong>Fecha:</strong> {{ $cita->fecha_formateada }} a las {{ $cita->hora_inicio_formateada }}</li>
                                        </ul>
                                    </div>
                                
                                    {{-- Nota del Cliente (si existe) --}}
                                    @if($cita->nota_cliente)
                                        <div class="alert alert-info mb-3">
                                            <strong><i class="bi bi-chat-quote"></i> Nota del Cliente:</strong>
                                            <p class="mb-0 mt-1">{{ $cita->nota_cliente }}</p>
                                        </div>
                                    @endif
                                    
                                    {{-- Razón del rechazo --}}
                                    <div class="mb-3">
                                        <label for="nota_rechazo_{{ $cita->id }}" class="form-label">
                                            <i class="bi bi-chat-left-text"></i> Razón del Rechazo *
                                        </label>
                                        <textarea 
                                            name="nota_admin" 
                                            id="nota_rechazo_{{ $cita->id }}"
                                            class="form-control" 
                                            rows="3" 
                                            placeholder="Ej: No hay disponibilidad en ese horario."
                                            required>{{ $cita->nota_admin }}</textarea>
                                        <small class="text-muted">Explica al cliente por qué se rechaza la cita.</small>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                        <i class="bi bi-arrow-left"></i> Cancelar
                                    </button>
                                    <button type="submit" class="btn btn-danger">
                                        <i class="bi bi-x-circle"></i> Rechazar Cita
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @endforeach
            </tbody>
        </table>

        {{ $citas->links() }}
    </div>
</div>
@endsection
