<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;

class AlertaController extends Controller
{
    public function enviarAlertas()
    {
        // Lista de destinatarios
        $destinatarios = [
            'carocorcho9@gmail.com',
            'zarethfuentes2@gmail.com',
            'brainerclemente12@gmail.com'
        ];

        // Recorremos cada correo y enviamos uno por uno
        foreach ($destinatarios as $correo) {
            Mail::raw('Este es un correo de prueba desde Laravel usando Brevo SMTP.', function ($message) use ($correo) {
                $message->to($correo)
                        ->subject('Correo de prueba Brevo');
            });
        }

        return 'Correos enviados correctamente!';
    }
}
