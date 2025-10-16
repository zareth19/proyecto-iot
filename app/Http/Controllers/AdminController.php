<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class AdminController extends Controller
{
    /**
     * inicio
     */
    public function index()
    {
        $usuario = Auth::user();
        return view('dashboard.admin-welcome', compact('usuario'));
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
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'numero_documento' => 'required|numeric|unique:users,numero_documento',
            'tipo_documento' => 'required|in:CC,TI,CE',
            'correo' => 'required|email|unique:users,correo',
            'telefono' => 'required|numeric',
            'rol' => 'required|string',
            'contraseña' => 'required|string|confirmed|min:6',
        ]);

        User::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'numero_documento' => $request->numero_documento,
            'tipo_documento' => $request->tipo_documento,
            'correo' => $request->correo,
            'telefono' => $request->telefono,
            'rol' => $request->rol,
            'contraseña' => bcrypt($request->contraseña),
        ]);

        return redirect()->route('admin.usuarios')->with('success', 'Usuario creado correctamente.');
    }

    /**
     * Actualizar datos del usuario
     */
    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'numero_documento' => 'required|numeric|unique:users,numero_documento,' . $usuario->id,
            'tipo_documento' => 'required|in:CC,TI,CE',
            'correo' => 'required|email|unique:users,correo,' . $usuario->id,
            'telefono' => 'required|numeric',
            'rol' => 'required|string',
        ]);

        $usuario->update([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
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
