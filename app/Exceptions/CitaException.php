<?php

namespace App\Exceptions;

use Exception;

class CitaException extends Exception
{
    /**
     * Horario no disponible
     */
    public static function horarioNoDisponible(): self
    {
        return new self('El horario seleccionado no está disponible.');
    }

    /**
     * Fuera de horario de atención
     */
    public static function fueraDeHorario(): self
    {
        return new self('La hora está fuera del horario de atención (9:00 - 19:00).');
    }

    /**
     * Servicio no activo
     */
    public static function servicioNoActivo(): self
    {
        return new self('El servicio seleccionado no está activo.');
    }

    /**
     * No se puede cancelar (menos de 2 horas)
     */
    public static function noPuedeCancelar(): self
    {
        return new self('Solo puedes cancelar citas con al menos 2 horas de anticipación.');
    }

    /**
     * Límite de citas alcanzado
     */
    public static function limiteCitasAlcanzado(): self
    {
        return new self('Has alcanzado el límite de citas pendientes (3). Espera a que sean aprobadas o canceladas.');
    }

    /**
     * Cita ya pasó
     */
    public static function citaPasada(): self
    {
        return new self('No puedes modificar una cita que ya ocurrió.');
    }

    /**
     * No es domingo
     */
    public static function esDomingo(): self
    {
        return new self('No se aceptan citas los domingos.');
    }
}