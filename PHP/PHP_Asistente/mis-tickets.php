<?php
session_start();
require_once "../conexion.php";

// ===============================
// 🔐 Verificar sesión
// ===============================
if (!isset($_SESSION['asistente'])) {
  header("Location: iniciar-asistente.php");
  exit();
}

$asistente = $_SESSION['asistente'];
$nombreUsuario = $asistente['nombre'] ?? 'Usuario';
$asistente_id = $asistente['asistente_id'] ?? $asistente['id'] ?? null;

// ===============================
// 📦 Obtener tickets del usuario
// ===============================
$sql = "SELECT t.*, c.nombre AS categoria, e.titulo, e.fecha_hora
        FROM tickets t
        JOIN categorias_entrada c ON t.categoria_id = c.categoria_id
        JOIN eventos e ON c.evento_id = e.evento_id
        WHERE t.asistente_id = ?
        ORDER BY t.fecha_emision DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $asistente_id);
$stmt->execute();
$tickets = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>🎟️ Mis Tickets - PUMFEST</title>
  <link rel="icon" type="image/png" href="../../LogoPUMFEST/LogoPUMFESTsinFondo.png" />
  <link rel="stylesheet" href="../../CSS/global.css">
  <link rel="stylesheet" href="../../CSS/CSS_Asistente/mis-tickets.css">

  <!-- 🔧 FontAwesome -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/js/all.min.js"
    crossorigin="anonymous"></script>
</head>

<body>
  <!-- =============================== HEADER =============================== -->
  <header class="header">
    <a href="../../index.php?skipVideo=1" class="logo" id="irInicio"></a>

    <div class="user-menu">
      <button id="userBtn" class="user-icon">
        <img src="../../LogoPUMFEST/LoginPUMFESTsinfondo.png" alt="Usuario" class="user-img" />
        <span class="login-text"><?= htmlspecialchars($nombreUsuario) ?></span>
      </button>

      <div id="userDropdown" class="user-dropdown">
        <a href="perfilAsistente.php"><i class="fa-solid fa-user"></i> Volver al perfil</a>
        <a href="../../PHP/cerrar-sesion.php" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Cerrar
          sesión</a>
      </div>
    </div>
  </header>

  <!-- =============================== CONTENIDO PRINCIPAL =============================== -->
  <main class="container-tickets">
    <h1>🎟️ Mis Tickets</h1>

    <div class="ticket-list">
      <?php if ($tickets->num_rows > 0): ?>
        <?php while ($t = $tickets->fetch_assoc()): ?>
          <div class="ticket-card">
            <div class="ticket-info">
              <h3><?= htmlspecialchars($t['titulo']) ?></h3>
              <p><strong>Categoría:</strong> <?= htmlspecialchars($t['categoria']) ?></p>
              <p><strong>Fecha:</strong> <?= date("d M Y H:i", strtotime($t['fecha_hora'])) ?></p>
              <p><strong>Estado:</strong>
                <span class="estado <?= $t['estado'] === 'activo' ? 'activo' : 'usado' ?>">
                  <?= htmlspecialchars($t['estado']) ?>
                </span>
              </p>
            </div>
            <!-- ==============================
              🎟️ SECCIÓN DEL QR CON BLOQUEO
            ============================== -->
            <div class="ticket-qr" data-codigo="<?= htmlspecialchars($t['codigo_qr']) ?>">
              <!-- Imagen real del QR -->
              <img src="../../IMG/QR_Tickets/<?= htmlspecialchars($t['codigo_qr']) ?>.png" alt="QR Ticket"
                class="qr-imagen">

              <!-- Imagen de bloqueo -->
              <div class="bloqueo-overlay"></div>

              <!-- Código del ticket -->
              <p class="codigo"><?= htmlspecialchars($t['codigo_qr']) ?></p>
            </div>

            <!-- botón de bloqueo -->
            <div class="ticket-actions">
              <button class="btn-bloquear">🔒 Volver a bloquear</button>
            </div>

          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="no-tickets">😔 Aún no has comprado tickets.</p>
      <?php endif; ?>
    </div>
  </main>

  <!-- =============================== FOOTER =============================== -->
  <footer class="footer">
    <div class="footer-bottom">
      <p>© 2025 PUMFEST. Todos los derechos reservados.</p>
    </div>
  </footer>

  <!-- =============================== JS =============================== -->


  <script src="../../JS/JS_Asistente/mis-tickets.js"></script>
  <!-- ==============================
    🔒 MODAL DE DESBLOQUEO DE QR
    ============================== -->
  <div id="modalDesbloqueo">
    <div class="modal-contenido">
      <span class="cerrar" id="cerrarModalDesbloqueo">&times;</span>
      <h3>🔐 Verificar identidad</h3>
      <p>Por seguridad, introduce tu contraseña para ver el QR.</p>
      <input type="password" id="inputPassword" placeholder="Contraseña del asistente" required>
      <button id="btnConfirmarDesbloqueo">Desbloquear QR</button>
    </div>
  </div>
</body>

</html>