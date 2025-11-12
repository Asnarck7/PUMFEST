<?php
session_start();
require_once "../conexion.php";

// ✅ Verificar sesión
if (!isset($_SESSION['organizador'])) {
    exit("sin_sesion");
}

$orgId = $_SESSION['organizador']['organizador_id'] ?? 0;
$evento_id = $_POST['id'] ?? 0;

if (!$evento_id || !$orgId) {
    exit("id_invalido");
}

// 🔍 Buscar datos del evento
$sql = "SELECT fecha_creacion, fecha_hora FROM eventos WHERE evento_id = ? AND organizador_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $evento_id, $orgId);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows === 0) {
    exit("no_autorizado");
}

$evento = $res->fetch_assoc();

// Fechas del evento
$fechaCreacion = new DateTime($evento['fecha_creacion']);
$fechaEvento   = new DateTime($evento['fecha_hora']);
$ahora         = new DateTime();

// 🧠 Calcular límites
$limiteEliminar = (clone $fechaCreacion)->modify('+1 day'); // puede eliminar solo durante 1 día después de crear
$despuesEvento  = (clone $fechaEvento)->modify('+1 day');   // se puede volver a eliminar 1 día después del evento

// 🧩 Lógica de eliminación
if ($ahora < $limiteEliminar) {
    // ✅ Dentro de las 24h después de crear
    $permitido = true;
} elseif ($ahora > $despuesEvento) {
    // ✅ Ya pasó un día después del evento
    $permitido = true;
} else {
    // ❌ No permitido en medio
    $permitido = false;
}

if (!$permitido) {
    exit("Solo puedes eliminar el evento dentro de las primeras 24 horas tras crearlo o 1 día después de la fecha del evento.");
}

// ✅ Eliminar el evento
$delete = $conn->prepare("DELETE FROM eventos WHERE evento_id = ? AND organizador_id = ?");
$delete->bind_param("ii", $evento_id, $orgId);

if ($delete->execute()) {
    echo "ok";
} else {
    echo "Error SQL: " . $conn->error;
}
?>
