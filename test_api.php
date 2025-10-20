<?php
// Probar API con datos reales

$url = 'http://localhost:8000/api/sensores';

echo "=== PROBANDO API SENSORES ===\n";
echo "URL: $url\n\n";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Código HTTP: $httpCode\n";
echo "Respuesta: $response\n";

if ($httpCode == 200) {
    $datos = json_decode($response, true);
    echo "\n✅ API funcionando!\n";
    echo "Registros encontrados: " . count($datos) . "\n";
    
    if (count($datos) > 0) {
        echo "\nÚltimo registro:\n";
        $ultimo = $datos[0];
        echo "- Fecha: " . $ultimo['fecha'] . "\n";
        echo "- Temperatura: " . $ultimo['temperatura'] . "°C\n";
        echo "- pH: " . $ultimo['ph'] . "\n";
        echo "- Turbidez: " . $ultimo['turbidez'] . " NTU\n";
    }
} else {
    echo "❌ Error en la API\n";
}
?>