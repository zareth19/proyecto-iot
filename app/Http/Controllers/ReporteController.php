<?php

namespace App\Http\Controllers;

use App\Models\SensorData;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ReporteController extends Controller
{
    public function index()
    {
        return view('admin.reportes');
    }

    public function generar(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date|before_or_equal:today',
            'fecha_fin' => 'required|date|before_or_equal:today|after_or_equal:fecha_inicio'
        ]);

        $fechaInicio = $request->fecha_inicio;
        $fechaFin = $request->fecha_fin;
        
        $datos = SensorData::whereBetween('fecha', [$fechaInicio, $fechaFin])
                          ->orderBy('fecha', 'desc')
                          ->get();

        $estadisticas = [
            'total_registros' => $datos->count(),
            'temp_promedio' => round($datos->avg('temperatura'), 2),
            'temp_max' => $datos->max('temperatura'),
            'temp_min' => $datos->min('temperatura'),
            'ph_promedio' => round($datos->avg('ph'), 2),
            'ph_max' => $datos->max('ph'),
            'ph_min' => $datos->min('ph'),
            'turbidez_promedio' => round($datos->avg('turbidez'), 2),
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
        
        $datos = SensorData::whereBetween('fecha', [$fechaInicio, $fechaFin])
                          ->orderBy('fecha', 'desc')
                          ->get();

        $estadisticas = [
            'total_registros' => $datos->count(),
            'temp_promedio' => round($datos->avg('temperatura'), 2),
            'temp_max' => $datos->max('temperatura'),
            'temp_min' => $datos->min('temperatura'),
            'ph_promedio' => round($datos->avg('ph'), 2),
            'ph_max' => $datos->max('ph'),
            'ph_min' => $datos->min('ph'),
            'turbidez_promedio' => round($datos->avg('turbidez'), 2),
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

        // Rangos ideales para tilapia
        if ($estadisticas['temp_promedio'] < 26 || $estadisticas['temp_promedio'] > 30) {
            $alertasTilapia[] = "Temperatura promedio ({$estadisticas['temp_promedio']}°C) fuera del rango ideal para tilapia [26-30°C]";
        }
        if ($estadisticas['ph_promedio'] < 6.5 || $estadisticas['ph_promedio'] > 9) {
            $alertasTilapia[] = "pH promedio ({$estadisticas['ph_promedio']}) fuera del rango ideal para tilapia [6.5-9]";
        }

        // Rangos ideales para cachama
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
}