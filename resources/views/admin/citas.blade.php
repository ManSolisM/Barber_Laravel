@extends('layouts.app')

@section('title', 'Gestión de Citas')

@section('content')
<h1><i class="bi bi-clipboard-check"></i> Gestión de Citas</h1>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card mt-4">
    <div class="card-body">

        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <select name="estado" class="form-select">
                    <option value="">Todos</option>
                    <option value="pendiente" {{ request('estado')=='pendiente'?'selected':'' }}>Pendiente</option>
                    <option value="aceptada" {{ request('estado')=='aceptada'?'selected':'' }}>Aceptada</option>
                    <option value="rechazada" {{ request('estado')=='rechazada'?'selected':'' }}>Rechazada</option>
                </select>
            </div>
            <div class="col-md-4">
                <input type="date" name="fecha" class="form-control" value="{{ request('fecha') }}">
            </div>
            <div class="col-md-4">
                <button class="btn btn-primary">Filtrar</button>
            </div>
        </form>

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
                    <td>
                        <span class="badge bg-{{ $cita->estado_badge }}">
                            {{ ucfirst($cita->estado) }}
                        </span>
                    </td>
                    <td>
                        @if($cita->isPendiente())
                        <button type="button"
                                class="btn btn-sm btn-success"
                                data-bs-toggle="modal"
                                data-bs-target="#aprobar{{ $cita->id }}">
                            Aprobar
                        </button>

                        <button type="button"
                                class="btn btn-sm btn-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#rechazar{{ $cita->id }}">
                            Rechazar
                        </button>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        {{ $citas->links() }}
    </div>
</div>
@foreach($citas as $cita)

{{-- MODAL APROBAR --}}
<div class="modal fade" id="aprobar{{ $cita->id }}" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.citas.aprobar', $cita->id) }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Aprobar cita</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="number" name="precio_final"
                           class="form-control mb-3"
                           step="0.01" min="0" required>

                    <textarea name="nota_admin"
                              class="form-control"
                              placeholder="Nota opcional"></textarea>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-success" type="submit">Aprobar</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- MODAL RECHAZAR --}}
<div class="modal fade" id="rechazar{{ $cita->id }}" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('admin.citas.rechazar', $cita->id) }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Rechazar cita</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <textarea name="nota_admin"
                              class="form-control"
                              required
                              placeholder="Motivo del rechazo"></textarea>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-danger" type="submit">Rechazar</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endforeach
@endsection
