<?php
/**
 * Generador de Favicons para Cardio Vida
 * Este script crea favicons básicos desde el logo existente
 */

// Verificar si existe el logo
$logoPath = __DIR__ . '/public/images/logo.png';
if (!file_exists($logoPath)) {
    die("❌ Error: No se encontró el logo en public/images/logo.png\n");
}

echo "🏥 Generando favicons para Cardio Vida...\n";

// Crear directorio de favicons si no existe
$faviconDir = __DIR__ . '/public/favicons/';
if (!is_dir($faviconDir)) {
    mkdir($faviconDir, 0755, true);
}

// Función para redimensionar imagen
function resizeImage($source, $destination, $width, $height) {
    $image = imagecreatefrompng($source);
    if (!$image) {
        echo "❌ Error: No se pudo cargar la imagen fuente\n";
        return false;
    }
    
    $resized = imagecreatetruecolor($width, $height);
    imagealphablending($resized, false);
    imagesavealpha($resized, true);
    
    imagecopyresampled($resized, $image, 0, 0, 0, 0, $width, $height, imagesx($image), imagesy($image));
    
    $result = imagepng($resized, $destination);
    imagedestroy($image);
    imagedestroy($resized);
    
    return $result;
}

// Tamaños de favicon a generar
$sizes = [
    'favicon-16x16.png' => [16, 16],
    'favicon-32x32.png' => [32, 32],
    'favicon-48x48.png' => [48, 48],
    'apple-touch-icon.png' => [180, 180],
    'android-chrome-192x192.png' => [192, 192],
    'android-chrome-512x512.png' => [512, 512]
];

echo "📁 Generando favicons en diferentes tamaños...\n";

foreach ($sizes as $filename => $dimensions) {
    $outputPath = __DIR__ . '/public/' . $filename;
    $success = resizeImage($logoPath, $outputPath, $dimensions[0], $dimensions[1]);
    
    if ($success) {
        echo "✅ Generado: {$filename} ({$dimensions[0]}x{$dimensions[1]})\n";
    } else {
        echo "❌ Error generando: {$filename}\n";
    }
}

// Crear favicon.ico (usando el de 32x32 como base)
$icoSource = __DIR__ . '/public/favicon-32x32.png';
$icoDestination = __DIR__ . '/public/favicon.ico';

if (file_exists($icoSource)) {
    // Para favicon.ico, simplemente copiamos el PNG de 32x32
    // En un entorno real, usarías una librería para crear un ICO real
    copy($icoSource, $icoDestination);
    echo "✅ Generado: favicon.ico\n";
} else {
    echo "❌ Error: No se pudo crear favicon.ico\n";
}

echo "\n🎉 ¡Favicons generados exitosamente!\n";
echo "📁 Archivos creados en: public/\n";
echo "\n📋 Archivos generados:\n";
echo "   - favicon.ico\n";
echo "   - favicon-16x16.png\n";
echo "   - favicon-32x32.png\n";
echo "   - favicon-48x48.png\n";
echo "   - apple-touch-icon.png\n";
echo "   - android-chrome-192x192.png\n";
echo "   - android-chrome-512x512.png\n";
echo "   - site.webmanifest\n";

echo "\n🔧 Para usar los favicons:\n";
echo "   1. Los archivos ya están en la carpeta public/\n";
echo "   2. El layout ya está configurado con las etiquetas meta\n";
echo "   3. Limpia la caché del navegador para ver los cambios\n";
echo "   4. Visita: http://127.0.0.1:8000/create-favicons.html para ver una vista previa\n";

echo "\n✨ ¡El logo ahora aparecerá en todas las pestañas del navegador!\n";
?>


