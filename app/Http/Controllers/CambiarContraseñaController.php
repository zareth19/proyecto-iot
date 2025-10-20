<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CambiarContraseñaController extends Controller
{
    public function mostrar()
    {
        return view('auth.cambiar-contraseña');
    }

    public function cambiar(Request $request)
    {
        $request->validate([
            'contraseña_actual' => 'required',
            'nueva_contraseña' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        // Verificar contraseña actual
        if (!Hash::check($request->contraseña_actual, $user->contraseña)) {
            return back()->withErrors(['contraseña_actual' => 'La contraseña actual es incorrecta']);
        }

        // Actualizar contraseña
        User::where('id', $user->id)->update([
            'contraseña' => bcrypt($request->nueva_contraseña),
            'debe_cambiar_contraseña' => false
        ]);

        // Redirigir según rol
        $rol = strtolower(trim($user->rol));
        switch ($rol) {
            case 'admin':
                return redirect()->route('dashboard.admin')->with('success', 'Contraseña cambiada exitosamente');
            case 'operario':
                return redirect()->route('dashboard.operario')->with('success', 'Contraseña cambiada exitosamente');
            case 'estandar':
                return redirect()->route('dashboard.estandar')->with('success', 'Contraseña cambiada exitosamente');
            default:
                return redirect()->route('login')->with('success', 'Contraseña cambiada exitosamente');
        }
    }
}