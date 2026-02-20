<?php

namespace App\Policies;

use App\Models\Cita;
use App\Models\User;

class CitaPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Todos pueden ver sus citas
    }

    public function view(User $user, Cita $cita): bool
    {
        return $user->isAdmin() || $user->id === $cita->user_id;
    }

    public function create(User $user): bool
    {
        return !$user->isAdmin(); // Solo clientes pueden crear
    }

    public function update(User $user, Cita $cita): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Cita $cita): bool
    {
        return $user->isAdmin();
    }

    public function cancelar(User $user, Cita $cita): bool
    {
        // El cliente solo puede cancelar sus propias citas pendientes
        return $user->id === $cita->user_id && $cita->estado === 'pendiente';
    }

    public function aprobar(User $user, Cita $cita): bool
    {
        return $user->isAdmin() && $cita->estado === 'pendiente';
    }
}