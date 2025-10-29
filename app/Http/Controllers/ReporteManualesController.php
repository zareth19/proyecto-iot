<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReporteManual;
use App\Models\SensorData;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteManualesController extends Controller
{
    public function index(Request $request)
    {
        $query = ReporteManual::with(['usuario', 'estanque']);
        
        if ($request->estanque_id) {
            $query->where('estanque_id', $request->estanque_id);
        }
        
        $reportes = $query->orderBy('fecha_toma', 'desc')->paginate(10);
        $estanques = \App\Models\Estanque::all();
            
        return view('operario.reportes-manuales', compact('reportes', 'estanques'));
    }

    public function crear()
    {
        $estanques = \App\Models\Estanque::where('estado', 'activo')->get();
        return view('operario.crear-reporte', compact('estanques'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'estanque_id' => 'required|exists:estanques,id',
            'temperatura' => 'required|numeric|min:0|max:50',
            'ph' => 'required|numeric|min:0|max:14',
            'turbidez' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string|max:500',
            'fecha_toma' => 'required|date'
        ]);

        ReporteManual::create([
            'usuario_id' => Auth::id(),
            'estanque_id' => $request->estanque_id,
            'temperatura' => $request->temperatura,
            'ph' => $request->ph,
            'turbidez' => $request->turbidez,
            'observaciones' => $request->observaciones,
            'fecha_toma' => $request->fecha_toma
        ]);

        return redirect()->route('operario.reportes.index')
            ->with('success', 'Reporte manual creado correctamente');
    }

    public function comparacion()
    {
        // Obtener reportes manuales del último mes
        $reportesManuales = ReporteManual::with('usuario')
            ->where('fecha_toma', '>=', now()->subMonth())
            ->orderBy('fecha_toma', 'desc')
            ->get();

        // Obtener datos de sensores del último mes
        $datosSensores = SensorData::where('fecha', '>=', now()->subMonth())
            ->orderBy('fecha', 'desc')
            ->get();

        return view('operario.comparacion', compact('reportesManuales', 'datosSensores'));
    }

    public function exportarPDF(Request $request)
    {
        $fechaInicio = $request->get('fecha_inicio', now()->subMonth()->format('Y-m-d'));
        $fechaFin = $request->get('fecha_fin', now()->format('Y-m-d'));
        
        $reportes = ReporteManual::with('usuario')
            ->whereBetween('fecha_toma', [$fechaInicio, $fechaFin])
            ->orderBy('fecha_toma', 'desc')
            ->get();

        $pdf = Pdf::loadView('operario.reportes-pdf', compact('reportes', 'fechaInicio', 'fechaFin'));
        
        return $pdf->download('reportes-manuales-' . now()->format('Y-m-d') . '.pdf');
    }

    public function descargarIndividual($id)
    {
        $reporte = ReporteManual::with('usuario')->findOrFail($id);
        
        $pdf = Pdf::loadView('operario.reporte-individual-pdf', compact('reporte'));
        
        return $pdf->download('reporte-manual-' . $reporte->id . '-' . $reporte->fecha_toma->format('Y-m-d') . '.pdf');
    }
}