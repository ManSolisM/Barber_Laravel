<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class StoreCitaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'servicio_id' => 'required|exists:servicios,id',
            'fecha' => [
                'required',
                'date',
                'after_or_equal:today',
                function ($attribute, $value, $fail) {
                    $dayOfWeek = Carbon::parse($value)->dayOfWeek;
                    if ($dayOfWeek == 0) { // Domingo
                        $fail('No se pueden agendar citas los domingos.');
                    }
                },
            ],
            'hora_inicio' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    $hora = Carbon::parse($value);
                    if ($hora->hour < 9 || $hora->hour >= 19) {
                        $fail('El horario debe estar entre 09:00 y 19:00.');
                    }
                },
            ],
            'nota_cliente' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'servicio_id.required' => 'Debe seleccionar un servicio.',
            'servicio_id.exists' => 'El servicio seleccionado no existe.',
            'fecha.required' => 'La fecha es obligatoria.',
            'fecha.after_or_equal' => 'La fecha debe ser hoy o posterior.',
            'hora_inicio.required' => 'La hora de inicio es obligatoria.',
            'hora_inicio.date_format' => 'El formato de hora debe ser HH:MM.',
            'nota_cliente.max' => 'La nota no puede exceder 500 caracteres.',
        ];
    }
}