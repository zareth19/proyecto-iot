<?php
// Verificar configuración de fotos
echo "=== VERIFICACIÓN DE FOTOS DE PERFIL ===\n\n";

// Verificar directorios
$storageDir = 'storage/app/public/fotos';
$publicDir = 'public/storage/fotos';

echo "1. Directorio storage: " . (is_dir($storageDir) ? "✅ Existe" : "❌ No existe") . "\n";
echo "2. Directorio público: " . (is_dir($publicDir) ? "✅ Existe" : "❌ No existe") . "\n";

// Verificar permisos
echo "3. Permisos storage: " . (is_writable($storageDir) ? "✅ Escribible" : "❌ No escribible") . "\n";
echo "4. Permisos público: " . (is_writable($publicDir) ? "✅ Escribible" : "❌ No escribible") . "\n";

// Verificar archivos
$files = glob($storageDir . '/*');
echo "5. Archivos en storage: " . count($files) . "\n";

$publicFiles = glob($publicDir . '/*');
echo "6. Archivos en público: " . count($publicFiles) . "\n";

if (count($files) > 0) {
    echo "\nArchivos encontrados:\n";
    foreach ($files as $file) {
        echo "- " . basename($file) . "\n";
    }
}
?>