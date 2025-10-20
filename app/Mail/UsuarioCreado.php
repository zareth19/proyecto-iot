<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UsuarioCreado extends Mailable
{
    use Queueable, SerializesModels;

    public $usuario;
    public $contraseñaTemporal;

    public function __construct(User $usuario, $contraseñaTemporal)
    {
        $this->usuario = $usuario;
        $this->contraseñaTemporal = $contraseñaTemporal;
    }

    public function build()
    {
        return $this->subject('Bienvenido al Sistema IoT - Credenciales de Acceso')
                    ->view('emails.usuario_creado');
    }
}