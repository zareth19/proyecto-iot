<?php 
namespace App\Services;

use App\Models\SensorData;
use App\Models\User;
use App\Notifications\AlertaNotificacion;

class VerificarSensores
{
    private $rangosIdeales = [
        'tilapia' => [
            'temperatura' => ['min' => 26, 'max' => 30],
            'ph' => ['min' => 6.5, 'max' => 9.0],
            'oxigeno_disuelto' => ['min' => 5.0, 'max' => INF], // > 5.0
            'amoniaco' => ['min' => -INF, 'max' => 0.05], // < 0.05
            'nitritos' => ['min' => -INF, 'max' => 0.5], // < 0.5
            'nitratos' => ['min' => -INF, 'max' => 100], // < 100
            'alcalinidad' => ['min' => 50, 'max' => 150],
            'dureza' => ['min' => 40, 'max' => 400],
            'turbidez' => ['min' => 20, 'max' => 50],
            'conductividad' => ['min' => 0, 'max' => INF], // No especificado, asumo cualquier valor por ahora
        ],
        'cachama' => [
            'temperatura' => ['min' => 24, 'max' => 29, 'tolerancia_min' => 22, 'tolerancia_max' => 34],
            'ph' => ['min' => 6.0, 'max' => 8.0, 'optimo' => 7.0],
            'oxigeno_disuelto' => ['min' => 4.0, 'max' => INF, 'minimo_tolerable' => 2.0], // > 4.0 (mínimo tolerable: 2.0)
            'amoniaco' => ['min' => 0.0, 'max' => 0.05],
            'nitritos' => ['min' => 0.0, 'max' => 0.5],
            'nitratos' => ['min' => -INF, 'max' => 100], // < 100
            'alcalinidad' => ['min' => 20, 'max' => INF, 'ideal' => 60], // > 20 (ideal: 60)
            'dureza' => ['min' => 20, 'max' => INF], // > 20
            'turbidez' => ['min' => 0, 'max' => INF], // No especificado
            'conductividad' => ['min' => -INF, 'max' => INF], // Baja a moderada, asumo cualquier valor por ahora
        ],
    ];

    public function ejecutar(SensorData $sensorData = null)
    {
        $sensores = ($sensorData) ? collect([$sensorData]) : SensorData::latest()->take(10)->get();

        $alertasGeneradas = [];

        foreach ($sensores as $sensor) {
            $alertasTilapia = $this->fueraDeRango($sensor, 'tilapia');
            $alertasCachama = $this->fueraDeRango($sensor, 'cachama');

            if (!empty($alertasTilapia) || !empty($alertasCachama)) {
                $alertasGeneradas[] = [
                    'sensor' => $sensor,
                    'alertas_tilapia' => $alertasTilapia,
                    'alertas_cachama' => $alertasCachama,
                ];

                $usuarios = User::where('recibe_alerta', true)->get();
                foreach ($usuarios as $usuario) {
                    $usuario->notify(new AlertaNotificacion($sensor, $alertasTilapia, $alertasCachama));
                }
            }
        }
        return $alertasGeneradas;
    }

    private function fueraDeRango(SensorData $sensor, string $especie)
    {
        $alertas = [];
        $rangos = $this->rangosIdeales[$especie];

        foreach ($rangos as $parametro => $rango) {
            $valorSensor = $sensor->{$parametro};

            // Para casos donde el valor es 'No especificado' o 'Baja a moderada'
            if (!is_numeric($valorSensor)) {
                continue; 
            }

            $min = $rango['min'] ?? -INF;
            $max = $rango['max'] ?? INF;

            // Manejo de casos especiales para Cachama y Tilapia
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
            } else { // Tilapia y otros casos generales
                if ($valorSensor < $min || $valorSensor > $max) {
                    $alertas[] = "{$parametro} ({$valorSensor}) fuera del rango ideal para {$especie} [{$min}-{$max}].";
                }
            }
        }

        return $alertas;
    }
}
