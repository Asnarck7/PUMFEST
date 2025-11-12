<?php
session_start();
require_once "../conexion.php";

// Verifica sesión temporal
if (!isset($_SESSION['verificar_correo'])) {
  http_response_code(403);
  exit("sin_sesion");
}

$usuario = $_SESSION['verificar_correo'];
$usuario_id = $usuario['id'];
$codigo = $_POST['codigo'] ?? '';

// Verifica el código
$sql = "SELECT * FROM codigos_verificacion 
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

// Verifica expiración
if (strtotime($registro['expira_en']) < time()) {
  exit("expirado");
}

// Marca el código como usado
$conn->prepare("UPDATE codigos_verificacion SET usado = 1 WHERE id = {$registro['id']}")->execute();

// 🔥 Solo marca el correo como verificado, NO toca la contraseña
$conn->prepare("UPDATE usuarios SET email_verificado = 1 WHERE usuario_id = {$usuario_id}")->execute();

// ✅ Crear sesión de asistente directamente si quieres
$_SESSION['asistente'] = [
  'id' => $usuario_id,
  'correo' => $usuario['correo'],
  'nombre' => $usuario['nombre'],
  'rol' => 'asistente'
];

// Limpia la sesión temporal
unset($_SESSION['verificar_correo']);

echo "verificado";
?>