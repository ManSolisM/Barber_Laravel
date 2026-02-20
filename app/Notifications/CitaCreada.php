<?php

namespace App\Notifications;

use App\Models\Cita;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CitaCreada extends Notification
{
    use Queueable;

    public function __construct(
        public Cita $cita
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nueva cita agendada - JAMELZ Barbería')
            ->greeting('¡Hola ' . $notifiable->nombre . '!')
            ->line('Tu cita ha sido agendada exitosamente.')
            ->line('Servicio: ' . $this->cita->servicio->nombre)
            ->line('Fecha: ' . $this->cita->fecha_formateada)
            ->line('Hora: ' . $this->cita->hora_inicio_formateada)
            ->line('Estado: Pendiente de aprobación')
            ->action('Ver mis citas', route('cliente.mis-citas'))
            ->line('¡Gracias por elegirnos!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'cita_id' => $this->cita->id,
            'servicio' => $this->cita->servicio->nombre,
            'fecha' => $this->cita->fecha_formateada,
            'hora' => $this->cita->hora_inicio_formateada,
        ];
    }
}