<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Mostrar lista de usuarios
    public function index()
    {
        $usuarios = User::all();
        return view('admin.usuarios', compact('usuarios'));
    }

    // Guardar un nuevo usuario
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'documento' => 'required|numeric|unique:users,numero_documento',
            'tipo_documento' => 'required|in:CC,TI,CE,PP',
            'correo' => 'required|email|unique:users,correo',
            'telefono' => 'required|regex:/^[0-9]{10}$/|digits:10',
            'rol' => 'required|in:admin,operario,estandar',
            'contraseña' => 'required|string|confirmed|min:6'
        ], [
            'telefono.regex' => 'El teléfono debe tener exactamente 10 dígitos',
            'telefono.digits' => 'El teléfono debe tener exactamente 10 dígitos'
        ]);

        User::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'numero_documento' => $request->documento,
            'tipo_documento' => $request->tipo_documento,
            'correo' => $request->correo,
            'telefono' => $request->telefono,
            'rol' => $request->rol,
            'contraseña' => Hash::make($request->contraseña),
            'debe_cambiar_contraseña' => true
        ]);


        return redirect()->route('admin.usuarios')->with('success', 'Usuario creado correctamente.');
    }

    // Eliminar usuario
    public function destroy($id)
    {
        $usuario = User::findOrFail($id);
        
        // Prevenir que el usuario se elimine a sí mismo
        if ($usuario->id === Auth::id()) {
            return redirect()->back()->with('error', 'No puedes eliminar tu propia cuenta mientras estás conectado.');
        }
        
        $usuario->delete();
        return redirect()->back()->with('success', 'Usuario eliminado correctamente.');
    }

    // Actualizar usuario
    public function update(Request $request, $id)
    {
        $usuario = User::findOrFail($id);
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'telefono' => 'required|regex:/^[0-9]{10}$/|digits:10',
            'rol' => 'required|in:admin,operario,estandar'
        ]);
        
        $usuario->update([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'telefono' => $request->telefono,
            'rol' => $request->rol
        ]);
        
        return response()->json(['success' => true]);
    }

    // Cambiar contraseña
    public function cambiarPassword(Request $request, $id)
    {
        $usuario = User::findOrFail($id);
        
        $request->validate([
            'nueva_password' => 'required|min:6|confirmed'
        ]);
        
        $usuario->update([
            'contraseña' => Hash::make($request->nueva_password),
            'debe_cambiar_contraseña' => false
        ]);
        
        return response()->json(['success' => true]);
    }
}
