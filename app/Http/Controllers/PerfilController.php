<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class PerfilController extends Controller
{
    public function index()
    {
        return view('perfil.index');
    }

    public function actualizar(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $datosActualizar = [
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'telefono' => $request->telefono
        ];

        if ($request->hasFile('foto')) {
            // Eliminar foto anterior si existe
            if ($user->foto) {
                $rutaAnterior = storage_path('app/public/fotos/' . $user->foto);
                if (file_exists($rutaAnterior)) {
                    unlink($rutaAnterior);
                }
            }
            
            $foto = $request->file('foto');
            $nombreFoto = time() . '_' . $user->id . '.' . $foto->getClientOriginalExtension();
            
            // Crear directorio si no existe
            $directorioDestino = storage_path('app/public/fotos');
            if (!is_dir($directorioDestino)) {
                mkdir($directorioDestino, 0755, true);
            }
            
            // Mover archivo
            $rutaCompleta = $directorioDestino . '/' . $nombreFoto;
            if ($foto->move($directorioDestino, $nombreFoto)) {
                $datosActualizar['foto'] = $nombreFoto;
            } else {
                return redirect()->back()->with('error', 'Error al subir la foto');
            }
        }

        User::where('id', $user->id)->update($datosActualizar);

        return redirect()->route('perfil.index')->with('success', 'Perfil actualizado correctamente');
    }
}