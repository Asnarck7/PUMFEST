<?php
session_start();
require_once "../conexion.php";

header("Content-Type: application/json");

// ✅ Validar sesión de administrador
if (!isset($_SESSION['admin'])) {
  echo json_encode(["status" => "error", "mensaje" => "No autorizado."]);
  exit();
}

$accion = $_POST['accion'] ?? '';
$id = intval($_POST['id'] ?? 0);

if (!$accion || !$id) {
  echo json_encode(["status" => "error", "mensaje" => "Datos incompletos."]);
  exit();
}

try {

  // ============================================================
  // 🎯 ACCIONES NORMALES (verificar organizadores o eventos)
  // ============================================================
  if (in_array($accion, ["aprobar", "rechazar", "ocultar", "activar"])) {

    switch ($accion) {
      case "aprobar":
        $sql = "UPDATE organizadores SET verificado = 1, verificado_por_admin = 1 WHERE organizador_id = ?";
        break;

      case "rechazar":
        $sql = "UPDATE organizadores SET verificado = 0, verificado_por_admin = 1 WHERE organizador_id = ?";
        break;

      case "ocultar":
        $sql = "UPDATE eventos SET estado = 'oculto' WHERE evento_id = ?";
        break;

      case "activar":
        $sql = "UPDATE eventos SET estado = 'activo' WHERE evento_id = ?";
        break;
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
      echo json_encode(["status" => "ok", "mensaje" => "Acción '$accion' ejecutada correctamente."]);
    } else {
      echo json_encode(["status" => "error", "mensaje" => "Error al aplicar la acción."]);
    }

    $stmt->close();
    exit;
  }

  // ============================================================
  // 🧩 NUEVO BLOQUE: SOLICITUDES DE ELIMINACIÓN DE ORGANIZADOR
  // ============================================================
  if (in_array($accion, ['aprobar_solicitud', 'rechazar_solicitud'])) {

    if ($accion === 'aprobar_solicitud') {
      try {
        $conn->begin_transaction();

        // 🧭 1. Buscar el usuario y el organizador relacionados
        $getOrg = $conn->prepare("
      SELECT o.usuario_id, o.organizador_id
      FROM solicitudes_eliminar_organizador s
      JOIN organizadores o ON o.organizador_id = s.organizador_id
      WHERE s.id = ?
    ");
        $getOrg->bind_param("i", $id);
        $getOrg->execute();
        $res = $getOrg->get_result();
        $org = $res->fetch_assoc();

        if (!$org) {
          throw new Exception("No se encontró la solicitud u organizador.");
        }

        $usuario_id = $org['usuario_id'];
        $organizador_id = $org['organizador_id'];

        // 🚫 Desactivar restricciones para evitar error temporalmente
        $conn->query("SET FOREIGN_KEY_CHECKS = 0");

        // ✅ Eliminar la solicitud primero
        $delSolicitud = $conn->prepare("DELETE FROM solicitudes_eliminar_organizador WHERE id = ?");
        $delSolicitud->bind_param("i", $id);
        $delSolicitud->execute();

        // ✅ Eliminar los eventos
        $delEventos = $conn->prepare("DELETE FROM eventos WHERE organizador_id = ?");
        $delEventos->bind_param("i", $organizador_id);
        $delEventos->execute();

        // ✅ Eliminar el organizador
        $delOrg = $conn->prepare("DELETE FROM organizadores WHERE organizador_id = ?");
        $delOrg->bind_param("i", $organizador_id);
        $delOrg->execute();

        // ✅ Eliminar el usuario
        $delUser = $conn->prepare("DELETE FROM usuarios WHERE usuario_id = ?");
        $delUser->bind_param("i", $usuario_id);
        $delUser->execute();

        // ✅ Reactivar restricciones
        $conn->query("SET FOREIGN_KEY_CHECKS = 1");

        $conn->commit();
        echo json_encode(["status" => "ok", "mensaje" => "✅ Cuenta del organizador eliminada correctamente."]);
      } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(["status" => "error", "mensaje" => "Error al eliminar cuenta: " . $e->getMessage()]);
      }
      exit;
    }


    if ($accion === 'rechazar_solicitud') {
      $update = $conn->prepare("UPDATE solicitudes_eliminar_organizador SET estado = 'rechazada' WHERE id = ?");
      $update->bind_param("i", $id);
      if ($update->execute()) {
        echo json_encode(["status" => "ok", "mensaje" => "❌ Solicitud rechazada correctamente."]);
      } else {
        echo json_encode(["status" => "error", "mensaje" => "No se pudo rechazar la solicitud."]);
      }
      exit;
    }
  }

  // ============================================================
  // 🚫 SI NO ENTRA A NINGUNA ACCIÓN VÁLIDA
  // ============================================================
  echo json_encode(["status" => "error", "mensaje" => "Acción no válida."]);

} catch (Exception $e) {
  echo json_encode(["status" => "error", "mensaje" => "Error del servidor: " . $e->getMessage()]);
}
