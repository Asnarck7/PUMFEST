<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once "../conexion.php";
require '../../vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

header("Content-Type: application/json; charset=utf-8");

// ✅ 1. Verificar sesión
if (!isset($_SESSION['asistente'])) {
    echo json_encode(["status" => "error", "mensaje" => "Debes iniciar sesión para comprar."]);
    exit;
}

$asistente = $_SESSION['asistente'];
$asistente_id = $asistente['asistente_id'] ?? $asistente['usuario_id'] ?? $asistente['id'] ?? null;

if (!$asistente_id) {
    echo json_encode(["status" => "error", "mensaje" => "ID de asistente inválido."]);
    exit;
}

// ✅ 2. Validar datos POST
$evento_id = intval($_POST['evento_id'] ?? 0);
$categoria_id = intval($_POST['categoria_id'] ?? 0);
$cantidad = intval($_POST['cantidad'] ?? 0);

if ($evento_id <= 0 || $categoria_id <= 0 || $cantidad <= 0) {
    echo json_encode(["status" => "error", "mensaje" => "Datos inválidos recibidos."]);
    exit;
}

$conn->begin_transaction();

try {
    // ✅ 3. Verificar disponibilidad
    $stmt = $conn->prepare("SELECT precio, cantidad_disponible FROM categorias_entrada WHERE categoria_id = ? AND evento_id = ?");
    $stmt->bind_param("ii", $categoria_id, $evento_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 0) {
        throw new Exception("Categoría no encontrada.");
    }

    $categoria = $res->fetch_assoc();
    $precio = $categoria['precio'];
    $cupos = intval($categoria['cantidad_disponible']);

    if ($cupos < $cantidad) {
        throw new Exception("No hay suficientes entradas disponibles.");
    }

    // ✅ 4. Crear pedido
    $total = $precio * $cantidad;
    $stmt = $conn->prepare("INSERT INTO pedidos (asistente_id, evento_id, total, estado) VALUES (?, ?, ?, 'completado')");
    $stmt->bind_param("iid", $asistente_id, $evento_id, $total);
    $stmt->execute();
    $pedido_id = $stmt->insert_id;

    // ✅ 5. Insertar ítem del pedido
    $stmtItem = $conn->prepare("INSERT INTO pedido_items (pedido_id, categoria_id, cantidad, precio_unit) VALUES (?, ?, ?, ?)");
    $stmtItem->bind_param("iiid", $pedido_id, $categoria_id, $cantidad, $precio);
    $stmtItem->execute();

    // ✅ 6. Generar tickets con QR
    $carpeta = "../../IMG/QR_Tickets";
    if (!is_dir($carpeta)) mkdir($carpeta, 0777, true);

    for ($i = 0; $i < $cantidad; $i++) {
        $codigoQR = "PUM-" . strtoupper(bin2hex(random_bytes(5)));
        $qr = QrCode::create($codigoQR)->setSize(250)->setMargin(10);
        $writer = new PngWriter();
        $writer->write($qr)->saveToFile("$carpeta/$codigoQR.png");

        $stmt2 = $conn->prepare("INSERT INTO tickets (pedido_id, categoria_id, asistente_id, codigo_qr, estado) VALUES (?, ?, ?, ?, 'activo')");
        $stmt2->bind_param("iiis", $pedido_id, $categoria_id, $asistente_id, $codigoQR);
        $stmt2->execute();
    }

    // ✅ 7. Actualizar inventario
    $stmt = $conn->prepare("UPDATE categorias_entrada SET cantidad_disponible = cantidad_disponible - ? WHERE categoria_id = ?");
    $stmt->bind_param("ii", $cantidad, $categoria_id);
    $stmt->execute();

    $stmt = $conn->prepare("UPDATE eventos SET limiteTickets = limiteTickets - ? WHERE evento_id = ?");
    $stmt->bind_param("ii", $cantidad, $evento_id);
    $stmt->execute();

    $conn->commit();

    echo json_encode([
        "status" => "ok",
        "mensaje" => "🎟️ Compra confirmada. Se generaron $cantidad tickets.",
        "pedido_id" => $pedido_id
    ]);
} catch (Exception $e) {
    $conn->rollback();
    echo json_encode(["status" => "error", "mensaje" => $e->getMessage()]);
}
?>