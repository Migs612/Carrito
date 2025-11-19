<?php
/*
 fetch_rawg_covers.php
 Descarga carátulas/imagenes de juegos desde la API RAWG y las guarda en public/assets/products/

 Uso:
 1) Crear una variable de entorno RAWG_API_KEY con tu API key (recomendado) o editar la constante abajo.
 2) Ejecutar desde la raíz del proyecto: php scripts/fetch_rawg_covers.php

 Nota: RAWG API docs: https://rawg.io/apidocs
*/

$apiKey = getenv('RAWG_API_KEY') ?: '';
if (empty($apiKey)) {
    echo "ERROR: No se encontró la variable de entorno RAWG_API_KEY.\n";
    echo "Registra en https://rawg.io/apidocs y configura RAWG_API_KEY.\n";
    // no se sale forzosamente para permitir edición directa si el usuario prefiere
}

$destDir = __DIR__ . '/../public/assets/products/';
if (!is_dir($destDir)) {
    if (!mkdir($destDir, 0755, true)) {
        echo "No se pudo crear la carpeta destino: $destDir\n";
        exit(1);
    }
}

$games = [
    // filename => game name to search
    'placeholder1.jpg' => 'PlayStation 5',
    'placeholder2.jpg' => 'Xbox Series X',
    'placeholder3.jpg' => 'Nintendo Switch OLED',
    'placeholder4.jpg' => 'The Last of Us Part I',
    'placeholder5.jpg' => 'FIFA 24',
    'placeholder6.jpg' => 'The Legend of Zelda: Tears of the Kingdom',
    'hero.jpg'         => 'video game console'
];

function rawg_search_first($query, $apiKey) {
    $params = http_build_query([
        'search' => $query,
        'page_size' => 1,
        'key' => $apiKey
    ]);
    $url = "https://api.rawg.io/api/games?$params";

    $opts = [
        'http' => [
            'method' => 'GET',
            'header' => "User-Agent: CarritoBot/1.0\r\n",
            'timeout' => 15
        ]
    ];
    $context = stream_context_create($opts);
    $json = @file_get_contents($url, false, $context);
    if (!$json) return null;
    $data = json_decode($json, true);
    if (!isset($data['results'][0])) return null;
    return $data['results'][0];
}

function download_file($url, $outPath) {
    $opts = [
        'http' => [
            'method' => 'GET',
            'header' => "User-Agent: CarritoBot/1.0\r\n",
            'timeout' => 30
        ]
    ];
    $context = stream_context_create($opts);
    $data = @file_get_contents($url, false, $context);
    if ($data === false) return false;
    return file_put_contents($outPath, $data) !== false;
}

/**
 * Optimize image: resize to max width, recompress, create WebP and square thumbnail.
 * Tries to use Imagick if available, otherwise falls back to GD.
 */
function optimize_image($path) {
    if (!file_exists($path)) return false;
    $info = getimagesize($path);
    if (!$info) return false;
    $mime = $info['mime'];

    $destDir = dirname($path);
    $baseName = pathinfo($path, PATHINFO_FILENAME);

    // Target sizes and qualities
    $maxWidth = 1200;
    $jpegQuality = 75;
    $webpQuality = 70;
    $thumbSize = 400; // square

    // Use Imagick if available
    if (class_exists('Imagick')) {
        try {
            $img = new Imagick($path);
            // Auto-orient and strip metadata
            if ($img->getImageOrientation()) $img->autoOrient();
            $img->stripImage();

            // Resize if wider than maxWidth
            $w = $img->getImageWidth();
            if ($w > $maxWidth) {
                $img->resizeImage($maxWidth, 0, Imagick::FILTER_LANCZOS, 1);
            }
            $img->setImageCompressionQuality($jpegQuality);
            $img->setImageFormat('jpeg');
            $img->writeImage($path); // overwrite optimized JPEG

            // Create WebP
            $webpPath = $destDir . '/' . $baseName . '.webp';
            $img->setImageFormat('webp');
            $img->setImageCompressionQuality($webpQuality);
            $img->writeImage($webpPath);

            // Create square thumbnail (center crop)
            $thumb = clone $img;
            $thumb->setImageFormat('webp');
            // reset size using original image to avoid already-resized webp
            $thumbOrig = new Imagick($path);
            $thumbOrig->cropThumbnailImage($thumbSize, $thumbSize);
            $thumbOrig->setImageCompressionQuality($webpQuality);
            $thumbPath = $destDir . '/' . $baseName . '_thumb.webp';
            $thumbOrig->writeImage($thumbPath);
            $thumbOrig->clear();

            $img->clear();
            return true;
        } catch (Exception $e) {
            // Fall through to GD fallback
        }
    }

    // GD fallback
    try {
        // Check GD availability: need at least imagecreatefromstring or specific loaders
        $gdAvailable = function_exists('imagecreatefromstring') && (function_exists('imagejpeg') || function_exists('imagewebp'));
        if (!$gdAvailable) {
            // No GD functions available; skip optimization gracefully
            echo "  Optimización (GD) no disponible en este PHP. Habilita la extensión GD o Imagick para optimización.\n";
            return false;
        }

        // Create source image using imagecreatefromstring for robustness
        $src = imagecreatefromstring(file_get_contents($path));
        if (!$src) return false;
        $width = imagesx($src);
        $height = imagesy($src);

        // Resize to max width if needed
        if ($width > $maxWidth) {
            $newW = $maxWidth;
            $newH = intval(($height / $width) * $newW);
            $tmp = imagecreatetruecolor($newW, $newH);
            // preserve PNG transparency if original was PNG
            if ($mime === 'image/png') {
                imagecolortransparent($tmp, imagecolorallocatealpha($tmp, 0, 0, 0, 127));
                imagealphablending($tmp, false);
                imagesavealpha($tmp, true);
            }
            imagecopyresampled($tmp, $src, 0,0,0,0,$newW,$newH,$width,$height);
            // overwrite JPEG/PNG
            imagejpeg($tmp, $path, $jpegQuality);
            imagedestroy($tmp);
        }

        // Create WebP if possible
        $webpPath = $destDir . '/' . $baseName . '.webp';
        if (function_exists('imagewebp')) {
            // load updated image
            $im = imagecreatefromstring(file_get_contents($path));
            imagewebp($im, $webpPath, $webpQuality);
            imagedestroy($im);
        }

        // Create square thumbnail
        $src2 = imagecreatefromstring(file_get_contents($path));
        $w2 = imagesx($src2);
        $h2 = imagesy($src2);
        $min = min($w2, $h2);
        $sx = intval(($w2 - $min)/2);
        $sy = intval(($h2 - $min)/2);
        $thumb = imagecreatetruecolor($thumbSize, $thumbSize);
        imagecopyresampled($thumb, $src2, 0,0, $sx, $sy, $thumbSize, $thumbSize, $min, $min);
        $thumbPath = $destDir . '/' . $baseName . '_thumb.webp';
        if (function_exists('imagewebp')) {
            imagewebp($thumb, $thumbPath, $webpQuality);
        } else {
            // fallback to jpeg
            $thumbPathJ = $destDir . '/' . $baseName . '_thumb.jpg';
            imagejpeg($thumb, $thumbPathJ, $jpegQuality);
        }
        imagedestroy($src2);
        imagedestroy($thumb);
        imagedestroy($src);
        return true;
    } catch (Exception $e) {
        return false;
    }
}

foreach ($games as $filename => $gameName) {
    echo "Buscando: $gameName ...\n";
    if (empty($apiKey)) {
        echo "  Aviso: RAWG_API_KEY no configurada. Saltando búsqueda automática para '$gameName'.\n";
        continue;
    }
    $res = rawg_search_first($gameName, $apiKey);
    if (!$res) {
        echo "  No se encontró resultado para: $gameName\n";
        continue;
    }
    $imageUrl = null;
    if (!empty($res['background_image'])) $imageUrl = $res['background_image'];
    // RAWG puede tener background_image_additional o assets en detalles
    if (!$imageUrl) {
        // intentar obtener detalles del juego por slug para más imágenes
        if (!empty($res['slug'])) {
            $slug = $res['slug'];
            $detailUrl = "https://api.rawg.io/api/games/" . urlencode($slug) . "?key=$apiKey";
            $json = @file_get_contents($detailUrl);
            if ($json) {
                $det = json_decode($json, true);
                if (!empty($det['background_image'])) $imageUrl = $det['background_image'];
                if (!$imageUrl && !empty($det['background_image_additional'])) $imageUrl = $det['background_image_additional'];
            }
        }
    }

    if (!$imageUrl) {
        echo "  No se encontró imagen para '$gameName' en RAWG.\n";
        continue;
    }

    $outPath = $destDir . $filename;
    echo "  Descargando imagen: $imageUrl -> $outPath ...\n";
    $ok = download_file($imageUrl, $outPath);
    if ($ok) echo "  Guardado: $outPath\n";
    else echo "  Error descargando $imageUrl\n";
    // Optimizar imagen recién descargada
    if ($ok) {
        echo "  Optimizando imagen: $outPath ...\n";
        $opt = optimize_image($outPath);
        if ($opt) echo "  Optimizada: $outPath\n";
        else echo "  Error al optimizar: $outPath\n";
    }
}

echo "Hecho.\n";

?>