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
                'turbidez' => 'required|numeric'
            ]);

            // Agregar fecha actual
            $datos['fecha'] = now();
            
            // Buscar estanque asociado (asumimos que hay un sensor activo)
            $estanque = \DB::table('sensores_final')
                ->join('estanques', 'sensores_final.estanque_id', '=', 'estanques.id')
                ->whereNotNull('sensores_final.estanque_id')
                ->first();
                
            if ($estanque) {
                $datos['estanque_id'] = $estanque->estanque_id;
            }

            // Guardar datos en base de datos
            $sensor = SensorData::create($datos);

            // Verificar alertas automáticamente solo si hay estanque asociado
            if ($estanque) {
                $this->verificarSensores->ejecutar($sensor, $estanque->tipo_cultivo);
            }

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
    public function obtenerDatos(Request $request)
{
    $limite = $request->get('limite', 10); // Por defecto 10 registros si no envían el parámetro
    $ultimosDatos = SensorData::latest('fecha')->take($limite)->get();
    return response()->json($ultimosDatos);
}

}