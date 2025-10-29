<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alerta;

class AlertaController extends Controller
{
    public function index()
    {
        $alertas = Alerta::with('sensor')
            ->orderBy('fecha_alerta', 'desc')
            ->paginate(20);
            
        return view('alertas.index', compact('alertas'));
    }

    public function marcarLeida($id)
    {
        $alerta = Alerta::findOrFail($id);
        $alerta->update(['leida' => true]);
        
        return response()->json(['success' => true]);
    }

    public function obtenerNoLeidas()
    {
        $alertas = Alerta::where('leida', false)
            ->orderBy('fecha_alerta', 'desc')
            ->take(5)
            ->get();
            
        return response()->json($alertas);
    }

    public function marcarTodasLeidas()
    {
        Alerta::where('leida', false)->update(['leida' => true]);
        
        return response()->json(['success' => true]);
    }

    public function contarNoLeidas()
    {
        $count = Alerta::where('leida', false)->count();
        
        return response()->json(['count' => $count]);
    }

    public function obtenerAlertas()
    {
        $alertas = Alerta::where('leida', false)
            ->orderBy('fecha_alerta', 'desc')
            ->take(10)
            ->get();
            
        return response()->json($alertas);
    }
}