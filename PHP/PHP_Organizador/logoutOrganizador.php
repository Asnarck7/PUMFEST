<?php
session_start();
session_destroy();

// Devolvemos un JSON, no una redirección directa
header('Content-Type: application/json');
echo json_encode([
    'ok' => true,
    'mensaje' => 'Tu sesión se ha cerrado correctamente.'
]);
exit;
?>
