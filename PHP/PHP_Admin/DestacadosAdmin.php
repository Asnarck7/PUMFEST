<?php
session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: adminLogin.php");
  exit();
}

require_once "../../PHP/conexion.php";

// 🔹 Marcar o desmarcar evento destacado
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["evento_id"])) {
  $id = intval($_POST["evento_id"]);
  $destacado = intval($_POST["destacado"]);
  $conn->query("UPDATE eventos SET es_destacado = $destacado WHERE evento_id = $id");
  header("Location: DestacadosAdmin.php");
  exit();
}

// 🔹 Obtener todos los eventos activos
$result = $conn->query("SELECT * FROM eventos WHERE estado='activo' ORDER BY fecha_hora ASC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Eventos Destacados | PUMFEST Admin</title>
  <link rel="stylesheet" href="../../CSS/CSS_Admin/adminTables.css">
</head>
<body>
  <div class="panel-container">
    <h1>⭐ Administrar Eventos Destacados</h1>

    <table style="width:100%; color:white; margin-top:20px;">
      <tr>
        <th>ID</th>
        <th>Título</th>
        <th>Ciudad</th>
        <th>Destacado</th>
        <th>Acción</th>
      </tr>

      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['evento_id'] ?></td>
          <td><?= htmlspecialchars($row['titulo']) ?></td>
          <td><?= htmlspecialchars($row['ciudad']) ?></td>
          <td><?= isset($row['es_destacado']) && $row['es_destacado'] ? '✅' : '❌' ?></td>
          <td>
            <form method="POST" style="display:inline;">
              <input type="hidden" name="evento_id" value="<?= $row['evento_id'] ?>">
              <input type="hidden" name="destacado" value="<?= isset($row['es_destacado']) && $row['es_destacado'] ? 0 : 1 ?>">
              <button type="submit" class="btn"
                style="background:<?= isset($row['es_destacado']) && $row['es_destacado'] ? '#e74c3c' : '#2ecc71' ?>;">
                <?= isset($row['es_destacado']) && $row['es_destacado'] ? 'Quitar' : 'Destacar' ?>
              </button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
    </table>

    <a href="panelAdmin.php" class="logout-btn" style="margin-top:20px;">⬅ Volver al panel</a>
  </div>
</body>
</html>
