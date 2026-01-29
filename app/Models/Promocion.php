<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promocion extends Model
{
    use HasFactory;

    protected $table = 'promociones';

    protected $fillable = [
        'nombre',
        'descripcion',
        'descuento_porcentaje',
        'descuento_fijo',
        'fecha_inicio',
        'fecha_fin',
        'solo_permanentes',
        'activo',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'descuento_porcentaje' => 'decimal:2',
        'descuento_fijo' => 'decimal:2',
        'solo_permanentes' => 'boolean',
        'activo' => 'boolean',
    ];

    /**
     * Scope para promociones vigentes
     */
    public function scopeVigentes($query)
    {
        return $query->where('activo', true)
                    ->where('fecha_inicio', '<=', now())
                    ->where('fecha_fin', '>=', now());
    }

    /**
     * Calcular descuento
     */
    public function calcularDescuento($precioBase): float
    {
        if ($this->descuento_porcentaje) {
            return $precioBase * ($this->descuento_porcentaje / 100);
        }
        
        return $this->descuento_fijo ?? 0;
    }

    /**
     * Aplicar descuento
     */
    public function aplicarDescuento($precioBase): float
    {
        return $precioBase - $this->calcularDescuento($precioBase);
    }
}
