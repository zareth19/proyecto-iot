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
            'telefono' => 'required|regex:/^[0-9]{10}$/|digits:10',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'telefono.required' => 'El teléfono es obligatorio',
            'telefono.regex' => 'El teléfono debe tener exactamente 10 dígitos',
            'telefono.digits' => 'El teléfono debe tener exactamente 10 dígitos',
            'foto.image' => 'El archivo debe ser una imagen',
            'foto.mimes' => 'La imagen debe ser JPG, PNG o GIF',
            'foto.max' => 'La imagen no debe superar los 2MB'
        ]);

        $datosActualizar = [
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
            
            // Usar Storage de Laravel para mejor manejo
            try {
                $rutaFoto = $foto->storeAs('fotos', $nombreFoto, 'public');
                $datosActualizar['foto'] = $nombreFoto;
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Error al subir la foto: ' . $e->getMessage());
            }
        }

        User::where('id', $user->id)->update($datosActualizar);

        return redirect()->route('perfil.index')->with('success', 'Perfil actualizado correctamente');
    }
}