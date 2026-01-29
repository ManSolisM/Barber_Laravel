@extends('layouts.app')

@section('title', 'Gestión de Servicios')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1><i class="bi bi-bag"></i> Gestión de Servicios</h1>
    </div>
    <div class="col text-end">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearServicio">
            <i class="bi bi-plus"></i> Nuevo Servicio
        </button>
    </div>
</div>

@if($servicios->count() == 0)
    <div class="alert alert-warning">
        <i class="bi bi-exclamation-triangle"></i> No hay servicios registrados.
        <strong>Crea el primero usando el botón "Nuevo Servicio"</strong>
    </div>
@endif

<div class="row">
    @foreach($servicios as $servicio)
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h5 class="card-title mb-0">{{ $servicio->nombre }}</h5>
                    <span class="badge bg-{{ $servicio->activo ? 'success' : 'secondary' }}">
                        {{ $servicio->activo ? 'Activo' : 'Inactivo' }}
                    </span>
                </div>
                
                <p class="card-text text-muted">{{ $servicio->descripcion }}</p>
                
                <div class="mb-2">
                    <strong><i class="bi bi-cash"></i> Precio:</strong> 
                    <span class="text-success">${{ number_format($servicio->precio_base, 2) }}</span>
                </div>
                
                <div class="mb-2">
                    <strong><i class="bi bi-clock"></i> Duración:</strong> 
                    {{ $servicio->duracion_formateada }}
                </div>
                
                <div class="mb-3">
                    <strong><i class="bi bi-calendar-check"></i> Citas:</strong> 
                    {{ $servicio->citas_count }}
                </div>
                
                <div class="btn-group w-100" role="group">
                    <button type="button" 
                            class="btn btn-sm btn-outline-primary" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editarServicio{{ $servicio->id }}">
                        <i class="bi bi-pencil"></i> Editar
                    </button>
                    
                    <form method="POST" 
                          action="{{ route('admin.servicios.toggle', $servicio) }}" 
                          style="display: inline;">
                        @csrf
                        <button type="submit" 
                                class="btn btn-sm btn-outline-{{ $servicio->activo ? 'warning' : 'success' }}">
                            <i class="bi bi-{{ $servicio->activo ? 'x-circle' : 'check-circle' }}"></i>
                            {{ $servicio->activo ? 'Desactivar' : 'Activar' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Modal Editar Servicio -->
        <div class="modal fade" id="editarServicio{{ $servicio->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="{{ route('admin.servicios.actualizar', $servicio) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Editar Servicio</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nombre *</label>
                                <input type="text" name="nombre" class="form-control" value="{{ $servicio->nombre }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Descripción</label>
                                <textarea name="descripcion" class="form-control" rows="3">{{ $servicio->descripcion }}</textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Precio Base *</label>
                                    <input type="number" name="precio_base" class="form-control" step="0.01" min="0" value="{{ $servicio->precio_base }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Duración (minutos) *</label>
                                    <input type="number" name="duracion_minutos" class="form-control" min="1" value="{{ $servicio->duracion_minutos }}" required>
                                </div>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" name="activo" class="form-check-input" id="activo{{ $servicio->id }}" value="1" {{ $servicio->activo ? 'checked' : '' }}>
                                <label class="form-check-label" for="activo{{ $servicio->id }}">
                                    Servicio activo
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Modal Crear Servicio -->
<div class="modal fade" id="crearServicio" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.servicios.crear') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Crear Nuevo Servicio</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nombre del Servicio *</label>
                        <input type="text" name="nombre" class="form-control" placeholder="Ej: Corte Clásico" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3" placeholder="Descripción breve del servicio"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Precio Base *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="precio_base" class="form-control" step="0.01" min="0" placeholder="150.00" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Duración (minutos) *</label>
                            <input type="number" name="duracion_minutos" class="form-control" min="1" placeholder="30" required>
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <small>
                            <i class="bi bi-info-circle"></i> 
                            El servicio se creará como <strong>activo</strong> por defecto.
                        </small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Crear Servicio
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection