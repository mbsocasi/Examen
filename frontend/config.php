<?php
// Configuración de la API
define('API_BASE_URL_CURSOS', 'http://localhost:8002/api/cursos');
define('API_BASE_URL_MATERIALES', 'http://localhost:8003/api/materiales');

// Funciones para realizar peticiones a la API
function callAPI($method, $url, $data = false) {
    $curl = curl_init();

    switch ($method) {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($data) curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            if ($data) curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            break;
        case "DELETE":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
            break;
        default:
            if ($data) $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    $result = curl_exec($curl);
    curl_close($curl);
    return json_decode($result, true);
}

// Obtener cursos y materiales
$cursos = callAPI('GET', API_BASE_URL_CURSOS, false) ?? [];
$materiales = callAPI('GET', API_BASE_URL_MATERIALES, false) ?? [];
?>