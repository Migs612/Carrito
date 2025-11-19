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
}

echo "Hecho.\n";

?>