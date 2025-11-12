<?php
//permite Verificar la contrasena para ver el qr
session_start();
require_once "../conexion.php";

header("Content-Type: application/json; charset=utf-8");

// ✅ Verificar sesión
if (!isset($_SESSION['asistente'])) {
    echo json_encode(["status" => "error", "mensaje" => "Sesión no iniciada"]);
    exit;
}

$asistente = $_SESSION['asistente'];
$usuario_id = $asistente['asistente_id'] ?? $asistente['id'] ?? null;

if (!$usuario_id) {
    echo json_encode(["status" => "error", "mensaje" => "Usuario inválido"]);
    exit;
}

// ✅ Obtener la contraseña desde la petición
$input = json_decode(file_get_contents("php://input"), true);
$password = trim($input['password'] ?? '');

if (empty($password)) {
    echo json_encode(["status" => "error", "mensaje" => "Contraseña vacía"]);
    exit;
}

// ✅ Buscar el hash del usuario
$stmt = $conn->prepare("SELECT password FROM usuarios WHERE usuario_id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "mensaje" => "Usuario no encontrado"]);
    exit;
}

$row = $result->fetch_assoc();
$hash = $row['password'];

// ✅ Verificar la contraseña
if (password_verify($password, $hash)) {
    echo json_encode(["status" => "ok", "mensaje" => "Contraseña correcta"]);
} else {
    echo json_encode(["status" => "error", "mensaje" => "Contraseña incorrecta"]);
}
?>

