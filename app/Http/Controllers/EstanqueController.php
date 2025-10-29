<?php

namespace App\Http\Controllers;

use App\Models\Estanque;
use App\Models\CultivoPez;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EstanqueController extends Controller
{
    public function index()
    {
        $estanques = Estanque::with(['sensores' => function($query) {
            $query->select('id', 'estanque_id');
        }, 'cultivoPez'])->get();
        
        $cultivoPeces = CultivoPez::where('activo', true)->get();
        
        $user = Auth::user();
        if ($user->rol === 'operario') {
            return view('operario.estanques.index', compact('estanques', 'cultivoPeces'));
        }
        
        return view('admin.estanques.index', compact('estanques', 'cultivoPeces'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cultivo_pez_id' => 'required|exists:cultivo_peces,id',
            'largo' => 'required|numeric|min:0.1',
            'ancho' => 'required|numeric|min:0.1',
            'descripcion' => 'nullable|string'
        ]);

        $data = $request->all();
        // Calcular área y capacidad automáticamente
        $data['area_m2'] = $data['largo'] * $data['ancho'];
        // Capacidad de peces: 10 peces por m² (densidad estándar para acuicultura)
        $data['capacidad'] = (int)($data['area_m2'] * 10);

        Estanque::create($data);
        return redirect()->back()->with('success', 'Estanque creado exitosamente');
    }

    public function edit($id)
    {
        $estanque = Estanque::findOrFail($id);
        return response()->json($estanque);
    }

    public function update(Request $request, $id)
    {
        $estanque = Estanque::findOrFail($id);
        
        // Validaciones dinámicas según los campos enviados
        $rules = [];
        
        if ($request->has('cultivo_pez_id')) {
            $rules['cultivo_pez_id'] = 'required|exists:cultivo_peces,id';
            $rules['descripcion'] = 'nullable|string';
        }
        
        if ($request->has('largo')) {
            $rules['largo'] = 'required|numeric|min:0.1';
            $rules['ancho'] = 'required|numeric|min:0.1';
            $rules['area_m2'] = 'required|numeric|min:0.01';
        }
        
        if ($request->has('cantidad_sembrada')) {
            $rules['cantidad_sembrada'] = 'required|integer|min:1';
            $rules['densidad_siembra'] = 'required|numeric|min:0.01';
        }
        
        $request->validate($rules);
        
        $estanque->update($request->all());
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        Estanque::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Estanque eliminado');
    }

    public function cambiarEstado(Request $request, $id)
    {
        $estanque = Estanque::findOrFail($id);
        $estanque->update(['estado' => $request->estado]);
        return response()->json(['success' => true, 'estado' => $request->estado]);
    }

    public function asignarSensor(Request $request, $id)
    {
        DB::table('sensores_final')->where('id', $request->sensor_id)
            ->update(['estanque_id' => $id]);
        return response()->json(['success' => true]);
    }

    public function quitarSensor($id)
    {
       DB::table('sensores_final')->where('estanque_id', $id)
            ->update(['estanque_id' => null]);
        return response()->json(['success' => true]);
    }
}