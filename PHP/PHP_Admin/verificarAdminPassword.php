<?php
session_start();
require_once "../conexion.php";
header("Content-Type: application/json");

// ✅ Validar sesión activa
if (!isset($_SESSION['admin'])) {
    echo json_encode(["status" => "error", "mensaje" => "Sesión no válida"]);
    exit;
}

// ✅ Solo método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["status" => "error", "mensaje" => "Método no permitido"]);
    exit;
}

$passIngresado = $_POST['password'] ?? '';
$adminEmail = $_SESSION['admin']['email'] ?? '';

if (empty($adminEmail)) {
    echo json_encode(["status" => "error", "mensaje" => "Falta el correo del administrador en la sesión"]);
    exit;
}

// ✅ Buscar hash de contraseña
$sql = "SELECT password FROM usuarios WHERE email = ? AND rol = 'administrador' LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $adminEmail);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo json_encode(["status" => "error", "mensaje" => "Administrador no encontrado"]);
    exit;
}

$admin = $res->fetch_assoc();
$hashReal = $admin['password'];

// ✅ Detectar tipo de hash (bcrypt o SHA256)
$isBcrypt = str_starts_with($hashReal, '$2y$');

// ✅ Verificar contraseña
$valida = $isBcrypt
    ? password_verify($passIngresado, $hashReal)
    : hash_equals($hashReal, hash("sha256", $passIngresado));

// ✅ Responder
if ($valida) {
    echo json_encode(["status" => "ok", "mensaje" => "Contraseña verificada correctamente"]);
} else {
    echo json_encode(["status" => "error", "mensaje" => "Contraseña incorrecta"]);
}
