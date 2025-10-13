<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\SensorData;

class AlertaNotificacion extends Notification
{
    use Queueable;

    protected $sensorData;
    protected $alertasTilapia;
    protected $alertasCachama;

    /**
     * Create a new notification instance.
     */
    public function __construct(SensorData $sensorData, array $alertasTilapia, array $alertasCachama)
    {
        $this->sensorData = $sensorData;
        $this->alertasTilapia = $alertasTilapia;
        $this->alertasCachama = $alertasCachama;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('⚠️ Alerta de Sensores Fuera de Rango')
            ->view('emails.sensor_alert', [
                'sensor' => $this->sensorData,
                'alertasTilapia' => $this->alertasTilapia,
                'alertasCachama' => $this->alertasCachama,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'sensor_id' => $this->sensorData->id,
            'alertas_tilapia' => $this->alertasTilapia,
            'alertas_cachama' => $this->alertasCachama,
            'fecha' => $this->sensorData->fecha,
        ];
    }
}
