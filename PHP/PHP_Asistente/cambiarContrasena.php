<?php
session_start();
require_once "../conexion.php";

// ==============================
// 🔐 Verificar sesión activa ASISTENTE
// ==============================
if (!isset($_SESSION['asistente'])) {
    http_response_code(403);
    exit("sin_sesion");
}

$usuario = $_SESSION['asistente'];
$usuario_id = $usuario['id'] ?? null;

if (!$usuario_id) {
    http_response_code(400);
    exit("usuario_invalido");
}

// ==============================
// 🧩 Capturar datos del formulario
// ==============================
$codigo = trim($_POST['codigo'] ?? '');
$nueva_contrasena = trim($_POST['nueva_contrasena'] ?? '');
$confirmar_contrasena = trim($_POST['confirmar_contrasena'] ?? '');

// ==============================
// ⚠️ Validaciones básicas
// ==============================
if ($codigo === '' || $nueva_contrasena === '' || $confirmar_contrasena === '') {
    exit("faltan_datos");
}

if ($nueva_contrasena !== $confirmar_contrasena) {
    exit("no_coinciden");
}

if (strlen($nueva_contrasena) < 6) {
    exit("contrasena_corta"); // ⚙️ puedes ajustar esta longitud mínima si quieres
}

// ==============================
// 🔍 Verificar el código
// ==============================
$sql = "SELECT id, expira_en 
        FROM codigos_verificacion 
        WHERE usuario_id = ? AND codigo = ? AND usado = 0 
        ORDER BY id DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $usuario_id, $codigo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    exit("invalido");
}

$registro = $result->fetch_assoc();

// ==============================
// ⏰ Verificar expiración
// ==============================
if (strtotime($registro['expira_en']) < time()) {
    exit("expirado");
}

// ==============================
// 🔑 Cambiar contraseña
// ==============================
$hash = password_hash($nueva_contrasena, PASSWORD_DEFAULT);

$update = $conn->prepare("UPDATE usuarios SET password = ? WHERE usuario_id = ?");
$update->bind_param("si", $hash, $usuario_id);

if (!$update->execute()) {
    exit("error_update");
}

// ==============================
// ✅ Marcar código como usado
// ==============================
$marcar = $conn->prepare("UPDATE codigos_verificacion SET usado = 1 WHERE id = ?");
$marcar->bind_param("i", $registro['id']);
$marcar->execute();

// ==============================
// 🎉 Respuesta final
// ==============================
echo "actualizada";
?>
