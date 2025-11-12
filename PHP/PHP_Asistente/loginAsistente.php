<?php
session_start();
require_once "../conexion.php";
header('Content-Type: application/json');

$response = ["ok" => false, "mensaje" => "Error desconocido."];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $correo = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Consulta preparada (campo correcto: email)
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();

        // Verificar contraseña cifrada
        if (password_verify($password, $usuario['password'])) {

            // Crear sesión según el rol
            if ($usuario['rol'] === 'asistente') {
                $_SESSION['asistente'] = [
                    'id' => $usuario['usuario_id'],
                    'nombre' => $usuario['nombre'],
                    'apellido' => $usuario['apellido'],
                    'correo' => $usuario['email'],
                    'rol' => $usuario['rol']
                ];
                $response = [
                    "ok" => true,
                    "redirect" => "../../index.php",
                    "mensaje" => "Inicio de sesión exitoso como asistente."
                ];
            } 
        } else {
            $response["mensaje"] = "⚠️ Contraseña incorrecta.";
        }
    } else {
        $response["mensaje"] = "❌ Correo no registrado.";
    }
}

// Enviar respuesta JSON
echo json_encode($response);
$conn->close(); //Si conexion.php no cierra automáticamente
exit;
?>