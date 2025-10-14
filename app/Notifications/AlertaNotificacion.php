<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\SensorData;
use App\Services\TwilioWhatsAppChannel;

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
        return ['mail', TwilioWhatsAppChannel::class];
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

    public function toTwilioWhatsApp(object $notifiable): string
    {
        $message = "🚨 *Alerta de Sensores Fuera de Rango* 🚨\n\n";
        $message .= "*ID del Sensor:* " . ($this->sensorData->id ?? 'N/A') . "\n";
        $message .= "*Fecha y Hora:* " . ($this->sensorData->fecha ?? 'N/A') . "\n\n";

        if (!empty($this->alertasTilapia)) {
            $message .= "*Alertas para Tilapia:*\n";
            foreach ($this->alertasTilapia as $alerta) {
                $message .= "- {$alerta}\n";
            }
            $message .= "\n";
        }

        if (!empty($this->alertasCachama)) {
            $message .= "*Alertas para Cachama:*\n";
            foreach ($this->alertasCachama as $alerta) {
                $message .= "- {$alerta}\n";
            }
            $message .= "\n";
        }

        $message .= "Por favor, revisa los detalles en el panel de control: " . url('/dashboard');

        return $message;
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
