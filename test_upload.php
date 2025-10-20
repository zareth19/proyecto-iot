<?php
// Crear imagen de prueba
$width = 200;
$height = 200;
$image = imagecreatetruecolor($width, $height);

// Colores
$bg = imagecolorallocate($image, 100, 200, 100);
$text_color = imagecolorallocate($image, 255, 255, 255);

// Llenar fondo
imagefill($image, 0, 0, $bg);

// Agregar texto
$text = "FOTO TEST";
imagestring($image, 5, 50, 90, $text, $text_color);

// Guardar imagen
$filename = 'test_' . time() . '.jpg';
$path = 'storage/app/public/fotos/' . $filename;

if (imagejpeg($image, $path)) {
    echo "✅ Imagen de prueba creada: $filename\n";
    echo "📁 Ruta: $path\n";
    echo "🔗 URL: " . "http://localhost/laravel/proyecto-iot/storage/fotos/$filename\n";
} else {
    echo "❌ Error creando imagen de prueba\n";
}

imagedestroy($image);

// Verificar que se creó
if (file_exists($path)) {
    echo "✅ Archivo existe físicamente\n";
    echo "📏 Tamaño: " . filesize($path) . " bytes\n";
} else {
    echo "❌ Archivo NO existe\n";
}
?>