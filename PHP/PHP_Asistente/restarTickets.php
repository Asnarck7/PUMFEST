<?php
session_start();
require_once "../conexion.php";
header("Content-Type: application/json");

// ✅ Verificar sesión del asistente
if (!isset($_SESSION['asistente'])) {
    echo json_encode(["status" => "error", "mensaje" => "Debes iniciar sesión para comprar."]);
    exit();
}

// ✅ Validar datos recibidos del frontend
$evento_id = isset($_POST['evento_id']) ? intval($_POST['evento_id']) : 0;
$categoria_id = isset($_POST['categoria_id']) ? intval($_POST['categoria_id']) : 0;
$cantidad = isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 0;

if ($evento_id <= 0 || $categoria_id <= 0 || $cantidad <= 0) {
    echo json_encode(["status" => "error", "mensaje" => "Datos inválidos."]);
    exit();
}

// ✅ Verificar cupos disponibles de la categoría
$sql = "SELECT cantidad_disponible FROM categorias_entrada WHERE categoria_id = ? AND evento_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $categoria_id, $evento_id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    echo json_encode(["status" => "error", "mensaje" => "Categoría no encontrada."]);
    exit();
}

$categoria = $res->fetch_assoc();
$cuposDisponibles = intval($categoria['cantidad_disponible']);

if ($cuposDisponibles < $cantidad) {
    echo json_encode(["status" => "error", "mensaje" => "No hay suficientes entradas disponibles."]);
    exit();
}

// ✅ Descontar de la categoría seleccionada
$nuevosCupos = $cuposDisponibles - $cantidad;
$updateCat = $conn->prepare("UPDATE categorias_entrada SET cantidad_disponible = ? WHERE categoria_id = ?");
$updateCat->bind_param("ii", $nuevosCupos, $categoria_id);
$updateCat->execute();

// ✅ Descontar también del total de tickets del evento
$conn->query("UPDATE eventos SET limiteTickets = limiteTickets - $cantidad WHERE evento_id = $evento_id");

// ✅ (Opcional) Registrar la compra o agregar al carrito
// Puedes insertar en una tabla “tickets” o “carrito_items” si lo deseas.

// ✅ Respuesta al frontend
echo json_encode([
    "status" => "ok",
    "mensaje" => "🎟️ Compra realizada correctamente. Se descontaron $cantidad entradas."
]);

$conn->close();
?>