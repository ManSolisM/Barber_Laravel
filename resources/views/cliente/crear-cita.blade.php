@extends('layouts.app')
@section('title', 'Nueva Cita')
@section('content')
<h1><i class="bi bi-plus-circle"></i> Agendar Nueva Cita</h1>
<div class="card mt-4">
    <div class="card-body">
        <form method="POST" action="{{ route('cliente.citas.guardar') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Servicio *</label>
                <select name="servicio_id" class="form-select" required id="servicioSelect">
                    <option value="">Seleccionar servicio</option>
                    @foreach($servicios as $servicio)
                    <option value="{{ $servicio->id }}" data-precio="{{ $servicio->precio_base }}" data-duracion="{{ $servicio->duracion_minutos }}">
                        {{ $servicio->nombre }} - ${{ number_format($servicio->precio_base, 2) }} ({{ $servicio->duracion_formateada }})
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Fecha *</label>
                <input type="date" name="fecha" class="form-control" min="{{ date('Y-m-d') }}" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Hora de Inicio *</label>
                <input type="time" name="hora_inicio" class="form-control" min="09:00" max="19:00" required>
                <small class="text-muted">Horario: 9:00 - 19:00</small>
            </div>
            <div class="mb-3">
                <label class="form-label">Nota (opcional)</label>
                <textarea name="nota_cliente" class="form-control" rows="3"></textarea>
            </div>
            <div id="precioEstimado" class="alert alert-info" style="display:none;">
                <strong>Precio estimado:</strong> <span id="precioMonto"></span>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-check"></i> Agendar Cita</button>
            <a href="{{ route('cliente.dashboard') }}" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script>
document.getElementById('servicioSelect').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const precio = selected.getAttribute('data-precio');
    if (precio) {
        document.getElementById('precioMonto').textContent = '$' + parseFloat(precio).toFixed(2);
        document.getElementById('precioEstimado').style.display = 'block';
    }
});
</script>
@endsection
