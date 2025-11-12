<?php
session_start();
require_once "../../conexion.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  header('Content-Type: application/json; charset=utf-8');

  // 🔒 Verificar sesión
  if (!isset($_SESSION['organizador'])) {
    echo json_encode(["status" => "error", "mensaje" => "No hay sesión iniciada."]);
    exit;
  }

  $organizador = $_SESSION['organizador'];
  $organizador_id = null;

  // 🧩 Verificar si el organizador_id está directamente en sesión
  if (isset($organizador['organizador_id'])) {
    $organizador_id = $organizador['organizador_id'];
  } elseif (isset($organizador['usuario_id'])) {
    // Buscar organizador_id desde la tabla organizadores
    $query = $conexion->prepare("SELECT organizador_id FROM organizadores WHERE usuario_id = ?");
    $query->bind_param("i", $organizador['usuario_id']);
    $query->execute();
    $res = $query->get_result();
    if ($fila = $res->fetch_assoc()) {
      $organizador_id = $fila['organizador_id'];
    }
    $query->close();
  }

  if (!$organizador_id) {
    echo json_encode(["status" => "error", "mensaje" => "No se pudo determinar el ID del organizador."]);
    exit;
  }

  // ✏️ Motivo
  $motivo = trim($_POST['motivo'] ?? '');
  if (empty($motivo)) {
    echo json_encode(["status" => "error", "mensaje" => "Debes escribir un motivo."]);
    exit;
  }

  // 🔗 Verificar conexión
  if (!isset($conexion) && isset($conn)) $conexion = $conn;
  if (!isset($conexion)) {
    echo json_encode(["status" => "error", "mensaje" => "Error de conexión a la base de datos."]);
    exit;
  }

  try {
    // 🕓 Verificar última solicitud del mismo organizador
    $check = $conexion->prepare("
      SELECT fecha_solicitud 
      FROM solicitudes_eliminar_organizador 
      WHERE organizador_id = ? 
      ORDER BY fecha_solicitud DESC 
      LIMIT 1
    ");
    $check->bind_param("i", $organizador_id);
    $check->execute();
    $result = $check->get_result();

if ($result && $result->num_rows > 0) {
  $row = $result->fetch_assoc();
  
  $ultimaSolicitud = new DateTime($row['fecha_solicitud']);
  $ahora = new DateTime();
  $diferencia = $ahora->getTimestamp() - $ultimaSolicitud->getTimestamp();

  if ($diferencia < 3600) { // 1 hora (3600 segundos)
    $restante = ceil((3600 - $diferencia) / 60);
    echo json_encode([
      "status" => "error",
      "mensaje" => "Solo puedes enviar una solicitud cada hora. Intenta nuevamente en {$restante} minutos."
    ]);
    exit;
  }
}


    // ✅ Insertar nueva solicitud
    $insert = $conexion->prepare("
      INSERT INTO solicitudes_eliminar_organizador (organizador_id, motivo, estado, fecha_solicitud)
      VALUES (?, ?, 'pendiente', NOW())
    ");
    $insert->bind_param("is", $organizador_id, $motivo);
    $insert->execute();

    if ($insert->affected_rows > 0) {
      echo json_encode(["status" => "ok", "mensaje" => "Solicitud enviada correctamente."]);
    } else {
      echo json_encode(["status" => "error", "mensaje" => "Error al guardar la solicitud."]);
    }

    $insert->close();
    $conexion->close();
  } catch (Exception $e) {
    echo json_encode(["status" => "error", "mensaje" => "Error interno: " . $e->getMessage()]);
  }

  exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Solicitud de eliminación | PUMFEST</title>
  <link rel="stylesheet" href="../../../CSS/CSS_Organizador/solicitudEliminarCuenta.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <div class="solicitud-container">
    <h1><i class="fa-solid fa-trash-can"></i> Solicitud de Eliminación</h1>
    <p class="desc">Indica el motivo por el que deseas eliminar tu cuenta. Un administrador revisará tu solicitud.</p>

    <form id="formEliminarCuenta">
      <label for="motivo"><strong>Motivo de la solicitud:</strong></label>
      <textarea id="motivo" name="motivo" placeholder="Escribe aquí el motivo..." required></textarea>

      <div class="botones">
        <button type="submit" class="btn enviar"><i class="fa-solid fa-paper-plane"></i> Enviar solicitud</button>
        <button type="button" class="btn cancelar" onclick="window.location.href='../panelOrganizador.php'"><i class="fa-solid fa-arrow-left"></i> Volver</button>
      </div>
    </form>
  </div>

  <script src="../../../JS/JS_Organizador/solicitudEliminarCuenta.js"></script>
</body>
</html>