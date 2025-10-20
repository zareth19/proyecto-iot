<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'tipo_documento' => 'required',
            'numero_documento' => 'required',
            'contraseña' => 'required',
        ]);

        $credentials = [
            'tipo_documento' => $request->tipo_documento,
            'numero_documento' => $request->numero_documento,
            'password' => $request->contraseña,
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $rol = strtolower(trim($user->rol ?? ''));

            switch ($rol) {
                case 'admin':
                    return redirect()->route('dashboard.admin');
                case 'operario':
                    return redirect()->route('dashboard.operario');
                case 'estandar':
                    return redirect()->route('dashboard.estandar');
                default:
                    Auth::logout();
                    return redirect()->route('login')->withErrors(['rol' => 'El usuario no tiene un rol válido asignado.']);
            }
        }

        return back()->withErrors(['login' => 'Credenciales incorrectas.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
