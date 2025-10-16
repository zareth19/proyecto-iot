<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorData;
use App\Services\VerificarSensores;

class SensorController extends Controller
{
    protected $verificarSensores;

    public function __construct(VerificarSensores $verificarSensores)
    {
        $this->verificarSensores = $verificarSensores;
    }

    // Endpoint para recibir datos de Arduino
    public function recibirDatos(Request $request)
    {
        try {
            $datos = $request->validate([
                'temperatura' => 'required|numeric',
                'ph' => 'required|numeric', 
                'oxigeno_disuelto' => 'required|numeric',
                'amoniaco' => 'required|numeric',
                'nitritos' => 'required|numeric',
                'nitratos' => 'required|numeric',
                'alcalinidad' => 'required|numeric',
                'dureza' => 'required|numeric',
                'turbidez' => 'required|numeric',
                'conductividad' => 'required|numeric'
            ]);

            // Agregar fecha actual
            $datos['fecha'] = now();

            // Guardar en base de datos
            $sensor = SensorData::create($datos);

            // Verificar alertas automáticamente
            $this->verificarSensores->ejecutar($sensor);

            return response()->json([
                'success' => true, 
                'id' => $sensor->id,
                'message' => 'Datos recibidos correctamente'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    // Obtener datos para el dashboard
    public function obtenerDatos()
    {
        $ultimosDatos = SensorData::latest('fecha')->take(10)->get();
        return response()->json($ultimosDatos);
    }
}