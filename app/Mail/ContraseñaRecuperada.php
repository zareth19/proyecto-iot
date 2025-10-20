<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContraseñaRecuperada extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $nuevaContraseña;

    public function __construct($usuario, $nuevaContraseña)
    {
        $this->usuario = $usuario;
        $this->nuevaContraseña = $nuevaContraseña;
    }

    public function build()
    {
        return $this->subject('Recuperación de Contraseña - Sistema IoT')
                    ->view('emails.contraseña-recuperada');
    }
}