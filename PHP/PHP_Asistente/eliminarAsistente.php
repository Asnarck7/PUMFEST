<?php
session_start();
require_once "../conexion.php";

// ==============================
// 🔐 Verificar sesión activa
// ==============================
if (!isset($_SESSION['asistente'])) {
  header("Location: iniciar-asistente.php");
  exit();
}

$usuario_id = $_SESSION['asistente']['id'];
$password = $_POST['password'] ?? '';

// ==============================
// 🔎 Verificar contraseña actual
// ==============================
$sql = "SELECT password FROM usuarios WHERE usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();

// ==============================
// 🗑️ Eliminar cuenta si es válida
// ==============================
if ($usuario && password_verify($password, $usuario['password'])) {
  $sqlEliminar = "DELETE FROM usuarios WHERE usuario_id = ?";
  $stmt = $conn->prepare($sqlEliminar);
  $stmt->bind_param("i", $usuario_id);
  $stmt->execute();

  session_destroy();
  header("Location: ../../index.php?msg=Cuenta eliminada correctamente"); //Ruta index 
  exit();
} else {
  header("Location: perfilAsistente.php?error=contraseña_incorrecta");
  exit();
}
?>