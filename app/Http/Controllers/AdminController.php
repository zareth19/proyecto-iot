<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


class AdminController extends Controller
{
    /**
     * Dashboard principal - redirige a sensores
     */
    public function index()
    {
        return $this->dashboardSensores();
    }

    /**
     * Mostrar lista de usuarios
     */
    public function usuarios()
    {
        $usuarios = User::all();
        return view('admin.usuarios', compact('usuarios'));
    }

    /**
     * Guardar nuevo usuario
     */
    public function store(Request $request)
    {
        $request->validate([
            'primer_nombre' => 'required|string|max:50',
            'segundo_nombre' => 'nullable|string|max:50',
            'primer_apellido' => 'required|string|max:50',
            'segundo_apellido' => 'nullable|string|max:50',
            'numero_documento' => 'required|string|unique:users,numero_documento',
            'tipo_documento' => 'required|in:CC,TI,CE,PP,Cédula de Ciudadanía,Tarjeta de Identidad,Cédula de Extranjería,Pasaporte',
            'correo' => 'required|email|unique:users,correo',
            'telefono' => 'required|string',
            'rol' => 'required|string',
        ]);

        // Contraseñas predeterminadas por rol
        $contraseñasPorRol = [
            'admin' => 'Admin2024*',
            'operario' => 'Operario2024*',
            'estandar' => 'Estandar2024*'
        ];

        $contraseñaTemporal = $contraseñasPorRol[$request->rol] ?? 'Usuario2024*';

        $usuario = User::create([
            'primer_nombre' => $request->primer_nombre,
            'segundo_nombre' => $request->segundo_nombre,
            'primer_apellido' => $request->primer_apellido,
            'segundo_apellido' => $request->segundo_apellido,
            'nombre' => trim($request->primer_nombre . ' ' . ($request->segundo_nombre ?? '')),
            'apellido' => trim($request->primer_apellido . ' ' . ($request->segundo_apellido ?? '')),
            'numero_documento' => $request->numero_documento,
            'tipo_documento' => $request->tipo_documento,
            'correo' => $request->correo,
            'telefono' => $request->telefono,
            'rol' => $request->rol,
            'contraseña' => bcrypt($contraseñaTemporal),
            'debe_cambiar_contraseña' => true
        ]);

        // Enviar email con contraseña temporal
        Mail::to($usuario->correo)->send(new \App\Mail\UsuarioCreado($usuario, $contraseñaTemporal));

        return redirect()->route('admin.usuarios')->with('success', 'Usuario creado correctamente. Se ha enviado un email con las credenciales.');
    }

    /**
     * Actualizar datos del usuario
     */
    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $request->validate([
            'primer_nombre' => 'required|string|max:50',
            'segundo_nombre' => 'nullable|string|max:50',
            'primer_apellido' => 'required|string|max:50',
            'segundo_apellido' => 'nullable|string|max:50',
            'numero_documento' => 'required|string|unique:users,numero_documento,' . $usuario->id,
            'tipo_documento' => 'required|in:CC,TI,CE,PP,Cédula de Ciudadanía,Tarjeta de Identidad,Cédula de Extranjería,Pasaporte',
            'correo' => 'required|email|unique:users,correo,' . $usuario->id,
            'telefono' => 'required|string',
            'rol' => 'required|string',
        ]);

        $usuario->update([
            'primer_nombre' => $request->primer_nombre,
            'segundo_nombre' => $request->segundo_nombre,
            'primer_apellido' => $request->primer_apellido,
            'segundo_apellido' => $request->segundo_apellido,
            'nombre' => trim($request->primer_nombre . ' ' . ($request->segundo_nombre ?? '')),
            'apellido' => trim($request->primer_apellido . ' ' . ($request->segundo_apellido ?? '')),
            'numero_documento' => $request->numero_documento,
            'tipo_documento' => $request->tipo_documento,
            'correo' => $request->correo,
            'telefono' => $request->telefono,
            'rol' => $request->rol,
        ]);

        return redirect()->route('admin.usuarios')->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Eliminar usuario
     */
    public function eliminarUsuario($id)
    {
        $usuario = User::findOrFail($id);
        $usuario->delete();

        return redirect()->route('admin.usuarios')->with('delete', 'Usuario eliminado correctamente.');
    }

    /**
     * Sensores 
     */
public function dashboardSensores() {
    return view('admin.sensores');
}

}
