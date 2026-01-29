@extends('layouts.app')

@section('title', 'Calendario de Disponibilidad')

@section('content')
<div class="row mb-4">
    <div class="col">
        <h1><i class="bi bi-calendar3"></i> Calendario de Disponibilidad</h1>
        <p class="text-muted">Consulta los horarios disponibles y ocupados (información privada oculta)</p>
    </div>
</div>

<div class="row">
    <div class="col-md-9">
        <div class="card">
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Leyenda</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <span class="badge bg-danger">OCUPADO</span>
                    <p class="small mt-1">Horario confirmado</p>
                </div>
                <div class="mb-3">
                    <span class="badge bg-warning">OCUPADO</span>
                    <p class="small mt-1">Horario pendiente</p>
                </div>
                <hr>
                <h6>Servicios Disponibles</h6>
                <ul class="list-unstyled small">
                    @foreach($servicios as $servicio)
                    <li class="mb-2">
                        <strong>{{ $servicio->nombre }}</strong><br>
                        <span class="text-muted">{{ $servicio->duracion_formateada }}</span><br>
                        <span class="text-success">${{ number_format($servicio->precio_base, 2) }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        
        @guest
        <div class="card mt-3">
            <div class="card-body text-center">
                <p>¿Quieres agendar una cita?</p>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm w-100 mb-2">Registrarse</a>
                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm w-100">Iniciar Sesión</a>
            </div>
        </div>
        @endguest
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        slotMinTime: '09:00:00',
        slotMaxTime: '20:00:00',
        allDaySlot: false,
        height: 'auto',
        events: {
            url: '{{ route("calendario.eventos") }}',
            failure: function() {
                alert('Error al cargar los eventos del calendario');
            }
        },
        eventClick: function(info) {
            alert('Este horario está OCUPADO\nServicio: ' + info.event.extendedProps.servicio);
        },
        businessHours: {
            daysOfWeek: [1, 2, 3, 4, 5, 6], // Lunes - Sábado
            startTime: '09:00',
            endTime: '19:00'
        }
    });
    calendar.render();
});
</script>
@endsection
