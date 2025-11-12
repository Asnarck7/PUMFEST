<?php
session_start();
if (!isset($_SESSION['admin'])) {
  header("Location: adminLogin.php");
  exit();
}

require_once "../../PHP/conexion.php";

// ================================
// 🔹 Obtener todos los eventos
// ================================
$eventos = $conn->query("SELECT evento_id, titulo FROM eventos ORDER BY titulo ASC");

// ================================
// 🔹 Filtrar asistentes si se selecciona evento
// ================================
$filtro_evento = isset($_GET['evento']) && $_GET['evento'] !== '' ? intval($_GET['evento']) : null;

$sql = "
  SELECT 
    u.nombre,
    u.apellido,
    u.email,
    u.telefono,
    e.titulo AS evento,
    c.nombre AS categoria,
    COUNT(t.ticket_id) AS total_tickets
  FROM tickets t
  INNER JOIN asistentes a ON t.asistente_id = a.asistente_id
  INNER JOIN usuarios u ON a.usuario_id = u.usuario_id
  INNER JOIN categorias_entrada c ON t.categoria_id = c.categoria_id
  INNER JOIN eventos e ON c.evento_id = e.evento_id
";

if ($filtro_evento) {
  $sql .= " WHERE e.evento_id = ? ";
}

$sql .= " GROUP BY u.usuario_id, e.evento_id, c.categoria_id
          ORDER BY u.nombre ASC";

$query = $conn->prepare($sql);

if ($filtro_evento) {
  $query->bind_param("i", $filtro_evento);
}

$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>🎫 Asistentes | PUMFEST Admin</title>
  <link rel="stylesheet" href="../../CSS/CSS_Admin/adminTables.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <div class="panel-container">
    <h1>🎫 Lista de Asistentes</h1>

    <!-- 🔍 FILTRO POR EVENTO -->
    <form method="GET" class="filtro-evento">
      <label for="evento">Filtrar por evento:</label>
      <select name="evento" id="evento" onchange="this.form.submit()">
        <option value="">— Todos los eventos —</option>
        <?php while ($e = $eventos->fetch_assoc()): ?>
          <option value="<?= $e['evento_id'] ?>" <?= $filtro_evento == $e['evento_id'] ? 'selected' : '' ?>>
            <?= htmlspecialchars($e['titulo']) ?>
          </option>
        <?php endwhile; ?>
      </select>
      <?php if ($filtro_evento): ?>
        <a href="verAsistentes.php" class="btn-limpiar">Limpiar</a>
      <?php endif; ?>
    </form>

    <p class="info-filtro">
      <?= $filtro_evento ? "Mostrando asistentes con compras en el evento seleccionado." : "Mostrando todos los asistentes y su total de tickets." ?>
    </p>

    <table>
      <tr>
        <th>Nombre</th>
        <th>Evento</th>
        <th>Categoría</th>
        <th>Tickets Comprados</th>
        <th>Correo</th>
        <th>Teléfono</th>
        <th>Acción</th>
      </tr>

      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['nombre'] . " " . $row['apellido']) ?></td>
            <td><?= htmlspecialchars($row['evento']) ?></td>
            <td><?= htmlspecialchars($row['categoria']) ?></td>
            <td><?= $row['total_tickets'] ?></td>
            <td class="locked">🔒 Oculto</td>
            <td class="locked">🔒 Oculto</td>
            <td>
              <button class="btn btn-ver" 
                data-email="<?= htmlspecialchars($row['email']) ?>" 
                data-tel="<?= htmlspecialchars($row['telefono']) ?>">
                Ver Datos
              </button>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="7" style="text-align:center;">No se encontraron registros para la selección actual.</td>
        </tr>
      <?php endif; ?>
    </table>

    <a href="panelAdmin.php" class="logout-btn">⬅ Volver al panel</a>
  </div>

  <script>
    document.querySelectorAll(".btn-ver").forEach(btn => {
      btn.addEventListener("click", async () => {
        const { value: pass } = await Swal.fire({
          title: "🔑 Confirmar contraseña",
          input: "password",
          inputLabel: "Introduce tu contraseña para ver los datos",
          inputPlaceholder: "Contraseña del administrador",
          showCancelButton: true,
          confirmButtonText: "Verificar",
        });

        if (!pass) return;

        const resp = await fetch("verificarAdminPassword.php", {
          method: "POST",
          body: new URLSearchParams({ password: pass }),
        }).then(r => r.json());

        if (resp.status === "ok") {
          Swal.fire({
            title: "📋 Datos del Asistente",
            html: `
              <p><strong>Email:</strong> ${btn.dataset.email}</p>
              <p><strong>Teléfono:</strong> ${btn.dataset.tel}</p>
            `,
            icon: "info",
            confirmButtonText: "Cerrar",
          });
        } else {
          Swal.fire("❌ Error", "Contraseña incorrecta", "error");
        }
      });
    });
  </script>
</body>
</html>
