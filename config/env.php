<?php
// Cargar variables desde el archivo .env
function cargarEnv($ruta)
{
    if (!file_exists($ruta)) return;

    $variables = parse_ini_file($ruta, false, INI_SCANNER_RAW);
    foreach ($variables as $clave => $valor) {
        $_ENV[$clave] = $valor;
    }
}

// Carga las variables del archivo .env en la raíz
cargarEnv(__DIR__ . '/../.env');
