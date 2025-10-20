<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\SensorData;
use App\Services\VerificarSensores;

echo "=== PROBANDO SISTEMA DE NOTIFICACIONES ===\n\n";

// 1. Verificar usuarios
$usuarios = User::whereNotNull('correo')->get();
echo "Usuarios encontrados: " . $usuarios->count() . "\n";
foreach ($usuarios as $user) {
    echo "- {$user->nombre} {$user->apellido} ({$user->correo})\n";
}

// 2. Obtener último sensor
$sensor = SensorData::orderBy('fecha', 'desc')->first();
echo "\nÚltimo sensor:\n";
echo "- ID: {$sensor->id}\n";
echo "- Fecha: {$sensor->fecha}\n";
echo "- Temperatura: {$sensor->temperatura}°C\n";
echo "- pH: {$sensor->ph}\n";
echo "- Turbidez: {$sensor->turbidez} NTU\n";

// 3. Verificar alertas
$servicio = new VerificarSensores();
$alertas = $servicio->ejecutar($sensor);

echo "\nAlertas generadas: " . count($alertas) . "\n";
if (count($alertas) > 0) {
    foreach ($alertas as $alerta) {
        echo "\nAlertas Tilapia:\n";
        foreach ($alerta['alertas_tilapia'] as $msg) {
            echo "- $msg\n";
        }
        echo "\nAlertas Cachama:\n";
        foreach ($alerta['alertas_cachama'] as $msg) {
            echo "- $msg\n";
        }
    }
    echo "\n✅ ¡Se enviaron notificaciones por email!\n";
} else {
    echo "\n❌ No se generaron alertas (valores dentro del rango)\n";
}
?>