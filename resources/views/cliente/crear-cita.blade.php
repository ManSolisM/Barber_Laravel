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
const servicioSelect = document.getElementById('servicioSelect');
const fechaInput = document.querySelector('input[name="fecha"]');
const horaInput = document.querySelector('input[name="hora_inicio"]');
const precioEstimado = document.getElementById('precioEstimado');
const precioMonto = document.getElementById('precioMonto');

// Crear elemento para mostrar disponibilidad
const availabilityDiv = document.createElement('div');
availabilityDiv.id = 'disponibilidad-feedback';
availabilityDiv.className = 'mt-2';
horaInput.parentElement.appendChild(availabilityDiv);

servicioSelect.addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    const precio = selected.getAttribute('data-precio');
    if (precio) {
        precioMonto.textContent = '$' + parseFloat(precio).toFixed(2);
        precioEstimado.style.display = 'block';
    }
    verificarDisponibilidad();
});

fechaInput.addEventListener('change', verificarDisponibilidad);
horaInput.addEventListener('change', verificarDisponibilidad);

async function verificarDisponibilidad() {
    const servicioId = servicioSelect.value;
    const fecha = fechaInput.value;
    const horaInicio = horaInput.value;

    if (!servicioId || !fecha || !horaInicio) {
        availabilityDiv.innerHTML = '';
        return;
    }

    try {
        const response = await fetch('{{ route("api.verificar-disponibilidad") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                fecha: fecha,
                hora_inicio: horaInicio,
                servicio_id: servicioId
            })
        });

        const data = await response.json();

        if (data.disponible) {
            availabilityDiv.innerHTML = `
                <div class="alert alert-success py-2">
                    <i class="bi bi-check-circle"></i> ${data.mensaje}
                </div>
            `;
        } else {
            availabilityDiv.innerHTML = `
                <div class="alert alert-danger py-2">
                    <i class="bi bi-x-circle"></i> ${data.mensaje}
                </div>
            `;
        }
    } catch (error) {
        console.error('Error al verificar disponibilidad:', error);
    }
}
</script>
@endsection
