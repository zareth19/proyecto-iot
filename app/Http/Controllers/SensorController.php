<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SensorData;
use App\Services\VerificarSensores;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SensorController extends Controller
{
    protected $verificarSensores;

    public function __construct(VerificarSensores $verificarSensores)
    {
        $this->verificarSensores = $verificarSensores;
    }

    public function recibirDatos(Request $request)
    {
        try {
            Log::info('Datos recibidos del ESP32', $request->all());
            
            $datos = $request->validate([
                'temperatura' => 'required|numeric',
                'ph' => 'required|numeric',
                'turbidez' => 'required|numeric'
            ]);

            $datos['fecha'] = now();
            
            // Buscar estanque asociado
            $estanque = DB::table('sensores_final')
                ->join('estanques', 'sensores_final.estanque_id', '=', 'estanques.id')
                ->whereNotNull('sensores_final.estanque_id')
                ->first();
                
            if ($estanque) {
                $datos['estanque_id'] = $estanque->estanque_id;
                Log::info('Estanque encontrado', ['tipo_cultivo' => $estanque->tipo_cultivo]);
            }

            // Guardar en BD
            $sensor = SensorData::create($datos);
            Log::info('Sensor guardado', ['id' => $sensor->id]);

            // Verificar alertas automáticamente
            if ($estanque) {
                $this->verificarSensores->ejecutar($sensor, $estanque->tipo_cultivo);
            } else {
                $this->verificarSensores->ejecutar($sensor);
            }

            return response()->json([
                'success' => true, 
                'id' => $sensor->id,
                'message' => 'Datos recibidos correctamente'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error procesando datos del sensor', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function obtenerDatos(Request $request)
    {
        $limite = $request->get('limite', 10);
        $ultimosDatos = SensorData::latest('fecha')->take($limite)->get();
        return response()->json($ultimosDatos);
    }
}