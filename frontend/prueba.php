<?php
function getCourseMaterials($courseId) {
    $url = "http://localhost:8002/api/cursos/{$courseId}/materialess";

    // Inicializar cURL
    $ch = curl_init();

    // Configurar opciones de cURL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Accept: application/json"
    ]);

    // Ejecutar la solicitud
    $response = curl_exec($ch);

    // Manejar errores de cURL
    if (curl_errno($ch)) {
        echo 'Error en cURL: ' . curl_error($ch);
        curl_close($ch);
        return null;
    }

    // Cerrar cURL
    curl_close($ch);

    // Decodificar la respuesta JSON
    return json_decode($response, true);
}

// Ejemplo de uso
$courseId = 9;
$materials = getCourseMaterials($courseId);

if ($materials) {
    echo "<pre>";
    print_r($materials);
    echo "</pre>";
} else {
    echo "Error fetching materials.";
}
?>
