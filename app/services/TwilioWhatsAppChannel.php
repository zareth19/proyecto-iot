<?php

namespace App\Services;

use Illuminate\Notifications\Notification;
use Twilio\Rest\Client;
use App\Notifications\AlertaNotificacion;

class TwilioWhatsAppChannel
{
    public function send($notifiable, Notification $notification)
    {
        if (!$notifiable->phone_number) {
            return;
        }

        if (!$notification instanceof AlertaNotificacion) {
            return; // O lanzar una excepción si se espera siempre AlertaNotificacion
        }

        $message = $notification->toTwilioWhatsApp($notifiable);

        if (empty($message)) {
            return;
        }

        $twilioSid = config('services.twilio.sid');
        $twilioToken = config('services.twilio.token');
        $twilioWhatsAppFrom = config('services.twilio.whatsapp_from');

        $twilio = new Client($twilioSid, $twilioToken);

        $twilio->messages->create(
            "whatsapp:{$notifiable->phone_number}",
            ['from' => $twilioWhatsAppFrom, 'body' => $message]
        );
    }
}
