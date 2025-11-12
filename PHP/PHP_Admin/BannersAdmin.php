<?php
session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: adminLogin.php");
  exit();
}
require_once "../../PHP/conexion.php";

// 🔹 Marcar o desmarcar como destacado
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["evento_id"])) {
  $id = intval($_POST["evento_id"]);
  $destacado = intval($_POST["destacado"]);
  $conn->query("UPDATE eventos SET destacado = $destacado WHERE evento_id = $id");
  header("Location: BannersAdmin.php");
  exit();
}

// 🔹 Obtener todos los eventos activos
$result = $conn->query("SELECT * FROM eventos WHERE estado='activo' ORDER BY fecha_hora ASC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Destacados | PUMFEST Admin</title>
  <link rel="stylesheet" href="../../CSS/CSS_Admin/adminTables.css">
</head>
<body>
  <div class="panel-container">
    <h1>⭐ Administrar Eventos en el BANNER</h1>
    <table style="width:100%; color:white; margin-top:20px;">
      <tr><th>ID</th><th>Título</th><th>Ciudad</th><th>Destacado</th><th>Acción</th></tr>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['evento_id'] ?></td>
          <td><?= htmlspecialchars($row['titulo']) ?></td>
          <td><?= htmlspecialchars($row['ciudad']) ?></td>
          <td><?= $row['destacado'] ? '✅' : '❌' ?></td>
          <td>
            <form method="POST" style="display:inline;">
              <input type="hidden" name="evento_id" value="<?= $row['evento_id'] ?>">
              <input type="hidden" name="destacado" value="<?= $row['destacado'] ? 0 : 1 ?>">
              <button type="submit" class="btn" 
                style="background:<?= $row['destacado'] ? '#e74c3c' : '#2ecc71' ?>;">
                <?= $row['destacado'] ? 'Quitar' : 'Destacar' ?>
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
