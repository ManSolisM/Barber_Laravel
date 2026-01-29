<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'precio_base',
        'duracion_minutos',
        'activo',
    ];

    protected $casts = [
        'precio_base' => 'decimal:2',
        'duracion_minutos' => 'integer',
        'activo' => 'boolean',
    ];

    /**
     * Relación con citas
     */
    public function citas()
    {
        return $this->hasMany(Cita::class);
    }

    /**
     * Scope para servicios activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Obtener duración formateada
     */
    public function getDuracionFormateadaAttribute(): string
    {
        $horas = floor($this->duracion_minutos / 60);
        $minutos = $this->duracion_minutos % 60;
        
        if ($horas > 0 && $minutos > 0) {
            return "{$horas}h {$minutos}min";
        } elseif ($horas > 0) {
            return "{$horas}h";
        } else {
            return "{$minutos}min";
        }
    }

    /**
     * Obtener precio formateado
     */
    public function getPrecioFormateadoAttribute(): string
    {
        return '$' . number_format($this->precio_base, 2);
    }
}
