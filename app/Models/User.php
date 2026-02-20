<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes; // AGREGAR SoftDeletes
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'apellidos',
        'edad',
        'telefono',
        'email',
        'password',
        'role',
        'tipo_cliente',
        'activo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'activo' => 'boolean',
        ];
    }

    /**
     * Relación con citas
     */
    public function citas()
    {
        return $this->hasMany(Cita::class);
    }

    /**
     * Verificar si es admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Verificar si es cliente permanente
     */
    public function isPermanente(): bool
    {
        return $this->tipo_cliente === 'permanente';
    }

    /**
     * Verificar si es cliente temporal
     */
    public function isTemporal(): bool
    {
        return $this->tipo_cliente === 'temporal';
    }

    /**
     * Obtener nombre completo
     */
    public function getNombreCompletoAttribute(): string
    {
        return "{$this->nombre} {$this->apellidos}";
    }

    /**
     * Scope para usuarios activos
     */
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope para clientes
     */
    public function scopeClientes($query)
    {
        return $query->where('role', 'cliente');
    }

    /**
     * Scope para clientes permanentes
     */
    public function scopePermanentes($query)
    {
        return $query->where('tipo_cliente', 'permanente');
    }

    /**
    * Scope para clientes temporales
    */
    public function scopeTemporales($query)
    {
        return $query->where('tipo_cliente', 'temporal');
    }

    /**
     * Scope para admins
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
    * Obtener citas futuras del usuario
    */
    public function citasFuturas()
    {
        return $this->citas()
            ->where('fecha', '>=', now()->toDateString())
            ->whereIn('estado', ['pendiente', 'aceptada'])
            ->orderBy('fecha')
            ->orderBy('hora_inicio');
    }

    /**
     * Verificar si el usuario puede agendar más citas
    */
    public function puedeAgendarCita(): bool
    {
        if ($this->isAdmin()) {
        return false;
        }

        // Limitar citas pendientes a 3 por cliente
        $citasPendientes = $this->citas()
            ->where('estado', 'pendiente')
            ->count();

        return $citasPendientes < 3;
    }

}
