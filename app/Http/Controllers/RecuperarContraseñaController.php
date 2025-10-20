<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RecuperarContraseñaController extends Controller
{
    public function mostrar()
    {
        return view('auth.recuperar-contraseña');
    }

    public function enviar(Request $request)
    {
        $request->validate([
            'correo' => 'required|email|exists:users,correo'
        ]);

        $usuario = User::where('correo', $request->correo)->first();
        
        // Generar nueva contraseña temporal
        $nuevaContraseña = 'Temp' . rand(1000, 9999) . '*';
        
        // Actualizar usuario
        User::where('id', $usuario->id)->update([
            'contraseña' => bcrypt($nuevaContraseña),
            'debe_cambiar_contraseña' => true
        ]);

        // Enviar email
        Mail::to($usuario->correo)->send(new \App\Mail\ContraseñaRecuperada($usuario, $nuevaContraseña));

        return redirect()->route('login')->with('success', 'Se ha enviado una nueva contraseña a tu correo electrónico.');
    }
}