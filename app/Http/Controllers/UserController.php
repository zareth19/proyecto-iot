<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // Mostrar lista de usuarios
    public function index()
    {
        $usuarios = User::all(); // O paginación si quieres
        return view('admin.usuarios', compact('usuarios'));
    }

    // Guardar un nuevo usuario
    public function store(Request $request)
    {
        // Validación de campos
        $request->validate([
    'nombre' => 'required|string|max:255',
    'apellido' => 'required|string|max:255',
    'documento' => 'required|numeric|unique:users,numero_documento',
    'tipo_documento' => 'required|in:CC,TI,CE',
    'correo' => 'required|email|unique:users,correo',
    'telefono' => 'required|numeric',
    'rol' => 'required|string',
    'contraseña' => 'required|string|confirmed|min:6', // confirmed revisa contraseña_confirmation
]);

User::create([
    'nombre' => $request->nombre,
    'apellido' => $request->apellido,
    'numero_documento' => $request->documento,
    'tipo_documento' => $request->tipo_documento,
    'correo' => $request->correo,
    'telefono' => $request->telefono,
    'rol' => $request->rol,
    'contraseña' => bcrypt($request->contraseña),
]);


        return redirect()->route('admin.usuarios')->with('success', 'Usuario creado correctamente.');
    }
}
