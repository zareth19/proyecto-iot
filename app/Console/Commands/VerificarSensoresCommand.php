<?php 
namespace App\Services;

use App\Models\SensorData;
use App\Models\User;
use App\Models\Alerta;
use App\Notifications\AlertaNotificacion;
use Illuminate\Support\Facades\Log;

class VerificarSensores
{
    private $rangosIdeales = [
        'tilapia' => [
            'temperatura' => ['min' => 26, 'max' => 30],
            'ph' => ['min' => 6.5, 'max' => 9.0],
            'oxigeno_disuelto' => ['min' => 5.0, 'max' => INF],
            'amoniaco' => ['min' => -INF, 'max' => 0.05],
            'nitritos' => ['min' => -INF, 'max' => 0.5],
            'nitratos' => ['min' => -INF, 'max' => 100],
            'alcalinidad' => ['min' => 50, 'max' => 150],
            'dureza' => ['min' => 40, 'max' => 400],
            'turbidez' => ['min' => 20, 'max' => 50],
            'conductividad' => ['min' => 0, 'max' => INF],
        ],
        'cachama' => [
            'temperatura' => ['min' => 24, 'max' => 29, 'tolerancia_min' => 22, 'tolerancia_max' => 34],
            'ph' => ['min' => 6.0, 'max' => 8.0, 'optimo' => 7.0],
            'oxigeno_disuelto' => ['min' => 4.0, 'max' => INF, 'minimo_tolerable' => 2.0],
            'amoniaco' => ['min' => 0.0, 'max' => 0.05],
            'nitritos' => ['min' => 0.0, 'max' => 0.5],
            'nitratos' => ['min' => -INF, 'max' => 100],
            'alcalinidad' => ['min' => 20, 'max' => INF, 'ideal' => 60],
            'dureza' => ['min' => 20, 'max' => INF],
            'turbidez' => ['min' => 0, 'max' => INF],
            'conductividad' => ['min' => -INF, 'max' => INF],
        ],
    ];

    public function ejecutar(SensorData $sensorData, $tipoCultivo = null)
    {
        $alertasGeneradas = [];

        // Si no se especifica el tipo de cultivo, revisa ambos
        $tiposAVerificar = $tipoCultivo ? [$tipoCultivo] : ['tilapia', 'cachama'];

        foreach ($tiposAVerificar as $tipo) {
            $alertas = $this->fueraDeRango($sensorData, $tipo);

            if (!empty($alertas)) {
                foreach ($alertas as $alerta) {
                    Alerta::create([
                        'sensor_id' => $sensorData->id,
                        'tipo' => $tipo,
                        'mensaje' => $alerta,
                        'nivel' => 'warning',
                        'leida' => false,
                        'fecha_alerta' => now()
                    ]);
                }

                $alertasGeneradas[$tipo] = $alertas;
            } else {
                $alertasGeneradas[$tipo] = []; // siempre devuelve la clave vacía si no hay alertas
            }
        }

        // Devolver las alertas generadas al controlador
        return $alertasGeneradas;
    }

    private function fueraDeRango(SensorData $sensor, string $especie)
    {
        $alertas = [];
        $rangos = $this->rangosIdeales[$especie];

        foreach ($rangos as $parametro => $rango) {
            $valorSensor = $sensor->{$parametro};

            if (!is_numeric($valorSensor)) {
                continue; 
            }

            $min = $rango['min'] ?? -INF;
            $max = $rango['max'] ?? INF;

            if ($especie === 'cachama') {
                if ($parametro === 'temperatura') {
                    if ($valorSensor < ($rango['tolerancia_min'] ?? $min) || $valorSensor > ($rango['tolerancia_max'] ?? $max)) {
                        $alertas[] = "{$parametro} ({$valorSensor}) fuera del rango de tolerancia para {$especie} [{$rango['tolerancia_min']}-{$rango['tolerancia_max']}].";
                    }
                } elseif ($parametro === 'oxigeno_disuelto') {
                    if ($valorSensor < ($rango['minimo_tolerable'] ?? $min)) {
                        $alertas[] = "{$parametro} ({$valorSensor}) por debajo del mínimo tolerable para {$especie} [mínimo tolerable: {$rango['minimo_tolerable']}].";
                    }
                } elseif ($parametro === 'alcalinidad') {
                    if ($valorSensor < $min) {
                        $alertas[] = "{$parametro} ({$valorSensor}) por debajo del mínimo para {$especie} [> {$min}].";
                    }
                } else {
                    if ($valorSensor < $min || $valorSensor > $max) {
                        $alertas[] = "{$parametro} ({$valorSensor}) fuera del rango ideal para {$especie} [{$min}-{$max}].";
                    }
                }
            } else { // Tilapia
                if ($valorSensor < $min || $valorSensor > $max) {
                    $alertas[] = "{$parametro} ({$valorSensor}) fuera del rango ideal para {$especie} [{$min}-{$max}].";
                }
            }
        }

        return $alertas;
    }
}
