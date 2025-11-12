<?php
session_start();
require_once "../conexion.php";

header("Content-Type: application/json");

// ✅ Verificar que el organizador esté logueado
if (!isset($_SESSION['organizador'])) {
    echo json_encode(["status" => "error", "mensaje" => "Sesión no válida."]);
    exit();
}

$org = $_SESSION['organizador'];
$passwordIngresada = $_POST['password'] ?? '';

if (empty($passwordIngresada)) {
    echo json_encode(["status" => "error", "mensaje" => "Contraseña requerida."]);
    exit();
}

$usuarioId = $org['usuario_id'];

// 🔹 Buscar la contraseña real en la base de datos
$stmt = $conn->prepare("SELECT password FROM usuarios WHERE usuario_id = ? AND rol = 'organizador' LIMIT 1");
$stmt->bind_param("i", $usuarioId);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo json_encode(["status" => "error", "mensaje" => "Organizador no encontrado."]);
    exit();
}

$user = $res->fetch_assoc();
$hashReal = $user['password'];

// 🔒 Detectar si es bcrypt o hash sha256
$isBcrypt = str_starts_with($hashReal, '$2y$');

$valida = $isBcrypt
    ? password_verify($passwordIngresada, $hashReal)
    : hash_equals($hashReal, hash("sha256", $passwordIngresada));

if ($valida) {
    echo json_encode(["status" => "ok", "mensaje" => "Contraseña verificada correctamente."]);
} else {
    echo json_encode(["status" => "error", "mensaje" => "Contraseña incorrecta."]);
}
