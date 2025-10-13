<?php

namespace App\Http\Controllers;


use App\Models\SensorData;
use App\Services\VerificarSensores;
use Illuminate\Http\Request;

class SensoresController extends Controller
{
    protected $verificarSensores;

    public function __construct(VerificarSensores $verificarSensores)
    {
        $this->verificarSensores = $verificarSensores;
    }

    public function verificar($id)
    {
        // Buscar el sensor en BD
        $sensor = SensorData::find($id);

        if (!$sensor) {
            return response()->json(['error' => 'Sensor no encontrado'], 404);
        }

        // Usar el servicio
        $alertas = $this->verificarSensores->ejecutar($sensor);

        return response()->json([
            'sensor' => $sensor,
            'alertas' => $alertas
        ]);
    }
}
