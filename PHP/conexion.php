<?php
$host = "localhost";
$usuario = "root";
$contrasena = "";
$base_datos = "pumfest_db"; 

$conn = new mysqli($host, $usuario, $contrasena, $base_datos);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Opcional: para que use UTF-8 emojis y texto
$conn->set_charset("utf8mb4");
?>
