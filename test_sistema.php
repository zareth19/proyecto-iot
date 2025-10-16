<?php
// Script de prueba para simular datos de tu Arduino ESP32

$url = 'http://localhost:8000/api/sensores';

// Datos simulando tu ESP32 con sensores reales
$datos = [
    'temperatura' => 26.5,    // DS18B20
    'ph' => 7.2,             // Sensor pH pin 34
    'turbidez' => 15.3,      // Sensor turbidez pin 35
    'oxigeno_disuelto' => 5.8,
    'amoniaco' => 0.03,
    'nitritos' => 0.2,
    'nitratos' => 45.0,
    'alcalinidad' => 85.0,
    'dureza' => 120.0,
    'conductividad' => 180.0
];

echo "=== PRUEBA SISTEMA PISCICOLA ===\n";
echo "Enviando datos a: $url\n";
echo "Datos: " . json_encode($datos, JSON_PRETTY_PRINT) . "\n\n";

$json = json_encode($datos);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($json)
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "Código HTTP: $httpCode\n";
echo "Respuesta: $response\n";

if ($error) {
    echo "❌ Error de conexión: $error\n";
} elseif ($httpCode == 200) {
    echo "✅ ¡Datos enviados correctamente!\n";
    echo "✅ El sistema de alertas verificará automáticamente\n";
} else {
    echo "❌ Error del servidor: $httpCode\n";
}

echo "\n=== SIGUIENTE PASO ===\n";
echo "1. Verificar dashboard: http://192.168.43.164:8000/dashboard\n";
echo "2. Probar alertas: http://192.168.43.164:8000/enviar-alerta\n";
echo "3. Subir código a ESP32\n";
?>