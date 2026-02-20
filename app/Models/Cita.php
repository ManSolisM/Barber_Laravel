<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Cita extends Model
{
    use HasFactory, SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'user_id',
        'servicio_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'precio_estimado',
        'precio_final',
        'estado',
        'nota_admin',
        'nota_cliente',
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora_inicio' => 'datetime:H:i',
        'hora_fin' => 'datetime:H:i',
        'precio_estimado' => 'decimal:2',
        'precio_final' => 'decimal:2',
    ];

    /**
     * Relación con usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con servicio
     */
    public function servicio()
    {
        return $this->belongsTo(Servicio::class);
    }

    /**
     * Scope para citas pendientes
     */
    public function scopePendientes($query)
    {
        return $query->where('estado', 'pendiente');
    }

    /**
     * Scope para citas aceptadas
     */
    public function scopeAceptadas($query)
    {
        return $query->where('estado', 'aceptada');
    }

    /**
     * Scope para citas futuras
     */
    public function scopeFuturas($query)
    {
        return $query->where('fecha', '>=', now()->toDateString());
    }

    /**
     * Scope para citas pasadas
     */
    public function scopePasadas($query)
    {
        return $query->where('fecha', '<', now()->toDateString());
    }

    /**
     * Verificar si la cita está pendiente
     */
    public function isPendiente(): bool
    {
        return $this->estado === 'pendiente';
    }

    /**
     * Verificar si la cita está aceptada
     */
    public function isAceptada(): bool
    {
        return $this->estado === 'aceptada';
    }

    /**
     * Obtener fecha formateada
     */
    public function getFechaFormateadaAttribute(): string
    {
        return $this->fecha->format('d/m/Y');
    }

    /**
     * Obtener hora inicio formateada
     */
    public function getHoraInicioFormateadaAttribute(): string
    {
        return Carbon::parse($this->hora_inicio)->format('H:i');
    }

    /**
     * Obtener hora fin formateada
     */
    public function getHoraFinFormateadaAttribute(): string
    {
        return Carbon::parse($this->hora_fin)->format('H:i');
    }

    /**
     * Obtener badge de estado
     */
    public function getEstadoBadgeAttribute(): string
    {
        $badges = [
            'pendiente' => 'warning',
            'aceptada' => 'success',
            'rechazada' => 'danger',
            'cancelada' => 'secondary',
        ];

        return $badges[$this->estado] ?? 'secondary';
    }

    /**
     * Obtener precio a mostrar
     */
    public function getPrecioMostrarAttribute(): string
    {
        $precio = $this->precio_final ?? $this->precio_estimado;
        return '$' . number_format($precio, 2);
    }

    /**
     * Scope para citas de hoy
    */
    public function scopeHoy($query)
    {
        return $query->whereDate('fecha', today());
    }
    
    /**
     * Scope para citas de esta semana
     */
    public function scopeEstaSemana($query)
    {
        return $query->whereBetween('fecha', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }
    
    /**
     * Verificar si la cita ya pasó
     */
    public function yaOcurrio(): bool
    {
        $fechaHora = Carbon::parse($this->fecha . ' ' . $this->hora_fin);
        return $fechaHora->isPast();
    }
    
    /**
     * Verificar si se puede cancelar
     */
    public function sePuedeCancelar(): bool
    {
        // Solo se puede cancelar si es pendiente y falta más de 2 horas
        if ($this->estado !== 'pendiente') {
            return false;
        }
    
        $fechaHora = Carbon::parse($this->fecha . ' ' . $this->hora_inicio);
        return $fechaHora->diffInHours(now()) > 2;
    }
    
    /**
     * Obtener duración en minutos
     */
    public function getDuracionAttribute(): int
    {
        $inicio = Carbon::parse($this->hora_inicio);
        $fin = Carbon::parse($this->hora_fin);
        return $inicio->diffInMinutes($fin);
    }

}
