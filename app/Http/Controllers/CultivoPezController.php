<?php

namespace App\Http\Controllers;

use App\Models\CultivoPez;
use Illuminate\Http\Request;

class CultivoPezController extends Controller
{
    public function index()
    {
        $cultivoPeces = CultivoPez::all();
        return view('admin.cultivo-peces.index', compact('cultivoPeces'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_especie' => 'required|string|max:100|unique:cultivo_peces',
            'nombre_cientifico' => 'nullable|string|max:150',
            'descripcion' => 'nullable|string',
            'densidad_recomendada' => 'nullable|numeric|min:0.1|max:50'
        ]);

        $data = $request->all();
        $data['densidad_recomendada'] = $request->densidad_recomendada ?? 10.0;

        CultivoPez::create($data);
        return redirect()->back()->with('success', 'Especie creada exitosamente');
    }

    public function edit($id)
    {
        $cultivoPez = CultivoPez::findOrFail($id);
        return response()->json($cultivoPez);
    }

    public function update(Request $request, $id)
    {
        $cultivoPez = CultivoPez::findOrFail($id);
        
        $request->validate([
            'nombre_especie' => 'required|string|max:100|unique:cultivo_peces,nombre_especie,' . $id,
            'nombre_cientifico' => 'nullable|string|max:150',
            'descripcion' => 'nullable|string',
            'densidad_recomendada' => 'nullable|numeric|min:0.1|max:50'
        ]);

        $data = $request->all();
        $data['densidad_recomendada'] = $request->densidad_recomendada ?? 10.0;

        $cultivoPez->update($data);
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $cultivoPez = CultivoPez::findOrFail($id);
        
        // Verificar si hay estanques usando esta especie
        if ($cultivoPez->estanques()->count() > 0) {
            return redirect()->back()->with('error', 'No se puede eliminar. Hay estanques usando esta especie.');
        }
        
        $cultivoPez->delete();
        return redirect()->back()->with('success', 'Especie eliminada exitosamente');
    }

    public function toggleActivo($id)
    {
        $cultivoPez = CultivoPez::findOrFail($id);
        $cultivoPez->update(['activo' => !$cultivoPez->activo]);
        return response()->json(['success' => true, 'activo' => $cultivoPez->activo]);
    }
}