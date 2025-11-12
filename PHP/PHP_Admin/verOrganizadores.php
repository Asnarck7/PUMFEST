<?php
session_start();
require_once "../conexion.php";

// ✅ Validar sesión
if (!isset($_SESSION['admin'])) {
  header("Location: adminLogin.php");
  exit();
}

// ✅ Traer organizadores verificados
$sql = "SELECT o.organizador_id, u.nombre, u.email, o.biografia
        FROM organizadores o
        JOIN usuarios u ON u.usuario_id = o.usuario_id
        WHERE o.verificado = 1";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Organizadores Verificados | PUMFEST</title>
  <link rel="stylesheet" href="../../CSS/CSS_Admin/verOrganizadores.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <div class="verificados-container">
    <h1>📋 Organizadores Verificados</h1>
    <p>Lista de todos los organizadores aprobados por el administrador.</p>

    <?php if ($resultado && $resultado->num_rows > 0): ?>
      <table>
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Biografía</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($org = $resultado->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($org['nombre']) ?></td>
              <td><?= htmlspecialchars($org['email']) ?></td>
              <td><?= htmlspecialchars($org['biografia']) ?></td>
              <td>
                <button class="btn-accion rechazar" data-id="<?= $org['organizador_id'] ?>">❌ Revocar</button>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p class="sin-datos">🕊️ No hay organizadores verificados aún.</p>
    <?php endif; ?>

    <button class="volver-btn" onclick="location.href='panelAdmin.php'">⬅️ Volver al Panel</button>
  </div>

  <!-- 🔹 Nuevo archivo JS externo -->
  <script src="../../JS/JS_Admin/verOrganizadores.js"></script>
</body>
</html>
