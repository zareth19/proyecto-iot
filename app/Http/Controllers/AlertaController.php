<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;
use App\Services\VerificarSensores;
use App\Models\SensorData;

class AlertaController extends Controller
{
    protected $verificarSensores;

    public function __construct(VerificarSensores $verificarSensores)
    {
        $this->verificarSensores = $verificarSensores;
    }

    public function enviarAlertas()
    {
        // Puedes crear un SensorData de prueba o recuperar uno existente para probar
        $sensorDePrueba = SensorData::find(1); // O el ID de un sensor que sabes que está fuera de rango

        if (!$sensorDePrueba) {
            return 'No se encontró un sensor para probar las alertas.';
        }

        $alertas = $this->verificarSensores->ejecutar($sensorDePrueba);

        if (empty($alertas)) {
            return 'No se generaron alertas para el sensor de prueba.';
        }

        return 'Alertas procesadas y enviadas correctamente (si aplica).';
    }
}
