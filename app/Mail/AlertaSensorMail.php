<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AlertaSensorMail extends Mailable
{
    use Queueable, SerializesModels;

    public $sensor;
    public $valor;

    public function __construct($sensor, $valor)
    {
        $this->sensor = $sensor;
        $this->valor = $valor;
    }

    public function build()
    {
        return $this->subject('Alerta de Sensor')
                    ->view('emails.alerta_sensor');
    }
}
