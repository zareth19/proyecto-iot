<?php

namespace App\Http\Controllers;

use App\Models\SensorData;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $estanques = \App\Models\Estanque::all();
        
        $query = \App\Models\ReporteManual::with(['usuario', 'estanque']);
        
        if ($request->estanque_id) {
            $query->where('estanque_id', $request->estanque_id);
        }
        
        $reportesManuales = $query->orderBy('fecha_toma', 'desc')->paginate(10);
        
        return view('admin.reportes', compact('estanques', 'reportesManuales'));
    }

    public function generar(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date|before_or_equal:today',
            'fecha_fin' => 'required|date|before_or_equal:today|after_or_equal:fecha_inicio'
        ]);

        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;
        
        $query = SensorData::whereBetween('fecha', [$fechaInicio, $fechaFin]);
        
        if ($request->estanque_id) {
            $query->where('estanque_id', $request->estanque_id);
        }
        
        $datos = $query->orderBy('fecha', 'desc')->get();

        $estadisticas = [
            'total_registros' => $datos->count(),
            'temp_promedio' => round($datos->avg(fn($d) => (float)$d->temperatura), 2),
            'temp_max' => $datos->max('temperatura'),
            'temp_min' => $datos->min('temperatura'),
            'ph_promedio' => round($datos->avg(fn($d) => (float)$d->ph), 2),
            'ph_max' => $datos->max('ph'),
            'ph_min' => $datos->min('ph'),
            'turbidez_promedio' => round($datos->avg(fn($d) => (float)$d->turbidez), 2),
        ];

        $analisisEspecies = $this->analizarEspecies($estadisticas);

        return response()->json([
            'datos' => $datos,
            'estadisticas' => $estadisticas,
            'analisisEspecies' => $analisisEspecies,
            'periodo' => ['inicio' => $fechaInicio, 'fin' => $fechaFin]
        ]);
    }

    public function exportar(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date|before_or_equal:today',
            'fecha_fin' => 'required|date|before_or_equal:today|after_or_equal:fecha_inicio'
        ]);

        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;
        
        $query = SensorData::whereBetween('fecha', [$fechaInicio, $fechaFin]);
        
        if ($request->estanque_id) {
            $query->where('estanque_id', $request->estanque_id);
        }
        
        $datos = $query->orderBy('fecha', 'desc')->get();

        $estadisticas = [
            'total_registros' => $datos->count(),
            'temp_promedio' => round($datos->avg(fn($d) => (float)$d->temperatura), 2),
            'temp_max' => $datos->max('temperatura'),
            'temp_min' => $datos->min('temperatura'),
            'ph_promedio' => round($datos->avg(fn($d) => (float)$d->ph), 2),
            'ph_max' => $datos->max('ph'),
            'ph_min' => $datos->min('ph'),
            'turbidez_promedio' => round($datos->avg(fn($d) => (float)$d->turbidez), 2),
        ];

        $analisisEspecies = $this->analizarEspecies($estadisticas);

        $pdf = Pdf::loadView('reportes.pdf', [
            'datos' => $datos,
            'estadisticas' => $estadisticas,
            'analisisEspecies' => $analisisEspecies,
            'fechaInicio' => $fechaInicio,
            'fechaFin' => $fechaFin
        ]);

        return $pdf->download('reporte_sensores_'.$fechaInicio.'_'.$fechaFin.'.pdf');
    }

    private function analizarEspecies($estadisticas)
    {
        $alertasTilapia = [];
        $alertasCachama = [];

        // --- Rangos ideales para Tilapia ---
        if ($estadisticas['temp_promedio'] < 26 || $estadisticas['temp_promedio'] > 30) {
            $alertasTilapia[] = "Temperatura promedio ({$estadisticas['temp_promedio']}°C) fuera del rango ideal para tilapia [26-30°C]";
        }
        if ($estadisticas['ph_promedio'] < 6.5 || $estadisticas['ph_promedio'] > 9) {
            $alertasTilapia[] = "pH promedio ({$estadisticas['ph_promedio']}) fuera del rango ideal para tilapia [6.5-9]";
        }

        // --- Rangos ideales para Cachama ---
        if ($estadisticas['temp_promedio'] < 24 || $estadisticas['temp_promedio'] > 32) {
            $alertasCachama[] = "Temperatura promedio ({$estadisticas['temp_promedio']}°C) fuera del rango ideal para cachama [24-32°C]";
        }
        if ($estadisticas['ph_promedio'] < 6 || $estadisticas['ph_promedio'] > 8) {
            $alertasCachama[] = "pH promedio ({$estadisticas['ph_promedio']}) fuera del rango ideal para cachama [6-8]";
        }

        return [
            'tilapia' => $alertasTilapia,
            'cachama' => $alertasCachama
        ];
    }

    public function exportarManuales(Request $request)
    {
        $query = \App\Models\ReporteManual::with(['usuario', 'estanque']);
        
        if ($request->estanque_id) {
            $query->where('estanque_id', $request->estanque_id);
        }
        
        if ($request->fecha_inicio) {
            $query->whereDate('fecha_toma', '>=', $request->fecha_inicio);
        }
        
        if ($request->fecha_fin) {
            $query->whereDate('fecha_toma', '<=', $request->fecha_fin);
        }
        
        $reportes = $query->orderBy('fecha_toma', 'desc')->get();
        
        $pdf = Pdf::loadView('admin.reportes-manuales-pdf', compact('reportes'));
        
        return $pdf->download('reportes-manuales-admin-' . now()->format('Y-m-d') . '.pdf');
    }

    public function descargarManualIndividual($id)
    {
        $reporte = \App\Models\ReporteManual::with(['usuario', 'estanque'])->findOrFail($id);
        
        $pdf = Pdf::loadView('admin.reporte-manual-individual-pdf', compact('reporte'));
        
        return $pdf->download('reporte-manual-' . $reporte->id . '-' . $reporte->fecha_toma->format('Y-m-d') . '.pdf');
    }
}
