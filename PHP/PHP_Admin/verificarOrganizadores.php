<?php
session_start();
require_once "../conexion.php";

// ✅ Validar sesión
if (!isset($_SESSION['admin'])) {
  header("Location: adminLogin.php");
  exit();
}

$admin = $_SESSION['admin'];

// ✅ Traer organizadores pendientes o verificados
$sql = "SELECT 
          o.organizador_id,
          u.nombre AS organizador_nombre,
          u.email,
          o.biografia,
          u.telefono,
          o.verificado,
          e.evento_id,
          e.titulo AS evento_titulo,
          e.estado
        FROM organizadores o
        JOIN usuarios u ON u.usuario_id = o.usuario_id
        LEFT JOIN eventos e ON e.organizador_id = o.organizador_id
        ORDER BY o.verificado ASC, u.nombre ASC";

$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Verificar Organizadores | PUMFEST</title>
  <link rel="stylesheet" href="../../CSS/CSS_Admin/verificarOrganizadores.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <div class="verificar-container">
    <h1>✅ Verificar Organizadores</h1>
    <p>Aprueba organizadores y gestiona la visibilidad de sus eventos.</p>

    <?php if ($resultado && $resultado->num_rows > 0): ?>
      <table>
        <thead>
          <tr>
            <th>Organizador</th>
            <th>Correo</th>
            <th>Biografía</th>
            <th>Contacto</th>
            <th>Evento</th>
            <th>Estado</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $resultado->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['organizador_nombre']) ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= htmlspecialchars($row['biografia']) ?></td>
              <td><?= htmlspecialchars($row['telefono'] ?: 'No registrado') ?></td> <!-- ✅ Teléfono -->
              <td><?= htmlspecialchars($row['evento_titulo'] ?? 'Sin evento') ?></td>
              <td><?= htmlspecialchars($row['estado'] ?: 'sin estado') ?></td>
              <td>
                <?php if (!$row['verificado']): ?>
                  <button class="btn-accion aprobar" data-id="<?= $row['organizador_id'] ?>">✅ Aprobar</button>
                <?php else: ?>
                  <?php if ($row['estado'] === 'activo'): ?>
                    <button class="btn-accion cambiar" data-accion="ocultar" data-id="<?= $row['evento_id'] ?>">👁️
                      Ocultar</button>
                  <?php else: ?>
                    <button class="btn-accion cambiar" data-accion="activar" data-id="<?= $row['evento_id'] ?>">👁️
                      Mostrar</button>
                  <?php endif; ?>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p class="sin-datos">🕊️ No hay organizadores registrados todavía.</p>
    <?php endif; ?>

    <button class="volver-btn" onclick="location.href='panelAdmin.php'">⬅️ Volver al Panel</button>
  </div>

  <!-- 🔹 JS separado -->
  <script src="../../JS/JS_Admin/verificarOrganizadores.js"></script>

</body>

</html>