<?php
session_start();
require_once "../conexion.php";

header("Content-Type: application/json");

// ✅ Validar sesión del administrador
if (!isset($_SESSION['admin'])) {
  echo json_encode(["status" => "error", "mensaje" => "No autorizado."]);
  exit();
}

// ✅ Leer el cuerpo JSON
$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? 0;

if (!$id) {
  echo json_encode(["status" => "error", "mensaje" => "Falta el ID del organizador."]);
  exit();
}

try {
  // 🔹 1. Revocar organizador
  $stmt = $conn->prepare("UPDATE organizadores SET verificado = 0 WHERE organizador_id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $stmt->close();

  // 🔹 2. Ocultar todos sus eventos
  $stmt2 = $conn->prepare("UPDATE eventos SET visible = 0, estado = 'oculto' WHERE organizador_id = ?");
  $stmt2->bind_param("i", $id);
  $stmt2->execute();
  $stmt2->close();

  echo json_encode([
    "status" => "ok",
    "mensaje" => "✅ Organizador revocado y eventos ocultos correctamente."
  ]);
} catch (Exception $e) {
  echo json_encode([
    "status" => "error",
    "mensaje" => "Error del servidor: " . $e->getMessage()
  ]);
}
