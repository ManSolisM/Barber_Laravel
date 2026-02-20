@extends('layouts.app')
@section('title', 'Gestión de Servicios')
@section('content')
<div class="row mb-4">
    <div class="col"><h1><i class="bi bi-bag"></i> Gestión de Servicios</h1></div>
    <div class="col text-end">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#crearServicio">
            <i class="bi bi-plus"></i> Nuevo Servicio
        </button>
    </div>
</div>
<div class="row">
    @foreach($servicios as $servicio)
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-body">
                <h5>{{ $servicio->nombre }}</h5>
                <p class="text-muted">{{ $servicio->descripcion }}</p>
                <p><strong>Precio:</strong> ${{ number_format($servicio->precio_base, 2) }}</p>
                <p><strong>Duración:</strong> {{ $servicio->duracion_formateada }}</p>
                <p><strong>Citas:</strong> {{ $servicio->citas_count }}</p>
                <span class="badge bg-{{ $servicio->activo ? 'success' : 'secondary' }}">
                    {{ $servicio->activo ? 'Activo' : 'Inactivo' }}
                </span>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
