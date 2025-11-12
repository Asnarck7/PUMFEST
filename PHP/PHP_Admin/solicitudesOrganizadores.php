<?php
session_start();
require_once "../conexion.php";

// ✅ Verificar sesión admin
if (!isset($_SESSION['admin'])) {
    header("Location: adminLogin.php");
    exit();
}

// ✅ Consultar las solicitudes de eliminación
$sql = "
SELECT 
  s.id AS solicitud_id,
  s.motivo,
  s.estado,
  s.fecha_solicitud,
  u.nombre,
  u.email,
  o.organizador_id
FROM solicitudes_eliminar_organizador s
JOIN organizadores o ON o.organizador_id = s.organizador_id
JOIN usuarios u ON u.usuario_id = o.usuario_id
ORDER BY s.fecha_solicitud DESC
";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Solicitudes de Eliminación | PUMFEST</title>
  <link rel="stylesheet" href="../../CSS/CSS_Admin/verificarOrganizadores.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
  <div class="verificar-container">
    <h1><i class="fa-solid fa-trash-can"></i> Solicitudes de Eliminación</h1>
    <p>Lista de solicitudes de organizadores que desean eliminar su cuenta.</p>

    <?php if ($resultado && $resultado->num_rows > 0): ?>
      <table>
        <thead>
          <tr>
            <th>Organizador</th>
            <th>Correo</th>
            <th>Motivo</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $resultado->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['nombre']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= nl2br(htmlspecialchars($row['motivo'])) ?></td>
              <td><?= htmlspecialchars($row['fecha_solicitud']) ?></td>
              <td><?= ucfirst(htmlspecialchars($row['estado'])) ?></td>
              <td>
                <?php if ($row['estado'] === 'pendiente'): ?>
                  <button class="btn-accion aprobar" data-id="<?= $row['solicitud_id'] ?>"><i class="fa-solid fa-check"></i> Aprobar</button>
                  <button class="btn-accion rechazar" data-id="<?= $row['solicitud_id'] ?>"><i class="fa-solid fa-xmark"></i> Rechazar</button>
                <?php else: ?>
                  <span>-</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p class="sin-datos"><i class="fa-solid fa-dove"></i> No hay solicitudes pendientes.</p>
    <?php endif; ?>

    <button class="volver-btn" onclick="location.href='panelAdmin.php'">
      <i class="fa-solid fa-arrow-left"></i> Volver al Panel
    </button>
  </div>

  <script src="../../JS/JS_Admin/solicitudesOrganizadores.js"></script>
</body>
</html>