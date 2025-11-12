<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once "conexion.php";

// ✅ Verificar si hay ID de evento
if (!isset($_GET['id']) || empty($_GET['id'])) {
  header("Location: ../index.php");
  exit;
}

$evento_id = intval($_GET['id']);

// ✅ Obtener datos del evento
$stmt = $conn->prepare("SELECT * FROM eventos WHERE evento_id = ?");
$stmt->bind_param("i", $evento_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  echo "<h2 style='text-align:center;color:white;'>Evento no encontrado</h2>";
  exit;
}

$evento = $result->fetch_assoc();

// Variables
$titulo = htmlspecialchars($evento['titulo']);
$descripcion = htmlspecialchars($evento['descripcion']);
$fecha = date("d M Y - H:i A", strtotime($evento['fecha_hora']));
$lugar = htmlspecialchars($evento['lugar']);
$ciudad = htmlspecialchars($evento['ciudad']);
$imagen = "../IMG/eventos/" . htmlspecialchars($evento['imagen']);

// ✅ Sesión del asistente
$logueado = isset($_SESSION['asistente']);
$nombreUsuario = $logueado ? htmlspecialchars($_SESSION['asistente']['nombre']) : "";
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= $titulo ?> - PUMFEST</title>

  <link rel="icon" type="image/png" href="../LogoPUMFEST/LogoPUMFESTsinFondo.png" />
  <link rel="stylesheet" href="../CSS/CSS_Asistente/info-evento.css" />
  <link rel="stylesheet" href="../CSS/global.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body>
  <!-- HEADER -->
  <header class="header">
    <div class="header-top">
      <a href="../index.php" class="logo-link">
        <div class="logo"></div>
      </a>

      <!-- Menú de asistente -->
      <div class="user-menu">
        <button id="userBtn" class="user-icon">
          <img src="../LogoPUMFEST/LoginPUMFESTsinfondo.png" alt="Usuario" class="user-img" />
          <span class="login-text">
            <div class="loader-container">
              <div class="flipping-cards">
                <?php
                $texto = $logueado ? $nombreUsuario : "login";
                $texto = explode(" ", trim($texto))[0];
                foreach (str_split($texto) as $letra) {
                  echo '<div class="card">' . htmlspecialchars($letra) . '</div>';
                }
                ?>
              </div>
            </div>
          </span>
        </button>

        <div id="userDropdown" class="user-dropdown">
          <?php if ($logueado): ?>
            <span class="user-name">👋 <?= htmlspecialchars($nombreUsuario) ?></span>
            <a href="PHP_Asistente/perfilAsistente.php"><i class="fa-solid fa-user"></i> Mi perfil</a>
            <a href="../PHP/cerrar-sesion.php" class="logout-btn">
              <i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
            </a>
          <?php else: ?>
            <a href="PHP_Asistente/iniciar-asistente.php"><i class="fa-solid fa-right-to-bracket"></i> Iniciar sesión</a>
            <a href="PHP_Asistente/crear-asistente.php"><i class="fa-solid fa-user-plus"></i> Crear cuenta</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </header>

  <!-- MAIN -->
  <div class="header-bottom">
    <div class="container">
      <div class="main-content">
        <div class="event-detail">
          <img src="<?= $imagen ?>" alt="<?= $titulo ?>" class="event-image" />

          <div class="event-text">
            <h1><?= $titulo ?></h1>
            <p><i class="fa-solid fa-calendar-days"></i> <?= $fecha ?></p>
            <p><i class="fa-solid fa-location-dot"></i> <?= $lugar ?> - <?= $ciudad ?></p>
            <p class="descripcion"><?= nl2br($descripcion) ?></p>

            <!-- ✅ Si está logueado lo lleva a comprar, si no al login -->
            <?php if ($logueado): ?>
              <button class="btn" onclick="window.location.href='PHP_Asistente/entradas-precio.php?id=<?= $evento_id ?>'">
                <i class="fa-solid fa-ticket"></i> Comprar Entrada
              </button>
            <?php else: ?>
              <button class="btn" onclick="window.location.href='PHP_Asistente/iniciar-asistente.php'">
                <i class="fa-solid fa-ticket"></i> Comprar Entrada
              </button>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- FOOTER -->
  <footer class="footer">
    <div class="footer-content">
      <div class="footer-section">
        <h4><i class="fa-solid fa-layer-group"></i> Categorías</h4>
        <a href="#"><i class="fa-solid fa-music"></i> Conciertos</a>
        <a href="#"><i class="fa-solid fa-masks-theater"></i> Teatros</a>
        <a href="#"><i class="fa-solid fa-futbol"></i> Deporte</a>
        <a href="#"><i class="fa-solid fa-children"></i> Familia</a>
        <a href="#"><i class="fa-solid fa-comments"></i> Foros</a>
        <a href="#"><i class="fa-solid fa-star"></i> Experiencias</a>
      </div>

      <div class="footer-section">
        <h4><i class="fa-solid fa-circle-info"></i> Sobre Nosotros</h4>
        <a href="#"><i class="fa-solid fa-blog"></i> Blog</a>
        <a href="#"><i class="fa-solid fa-briefcase"></i> Carreras</a>
        <a href="#"><i class="fa-solid fa-ticket"></i> Entradas para tu evento</a>
        <a href="#"><i class="fa-solid fa-file-contract"></i> Términos de venta</a>
      </div>

      <div class="footer-section">
        <h4><i class="fa-solid fa-hashtag"></i> Redes Sociales</h4>
        <a href="#"><i class="fa-brands fa-facebook"></i> Facebook</a>
        <a href="#"><i class="fa-brands fa-instagram"></i> Instagram</a>
        <a href="#"><i class="fa-brands fa-x-twitter"></i> X (Twitter)</a>
      </div>

      <div class="footer-qr">
        <img src="../IMG/qrpumfest.png" alt="QR del sitio" />
        <p>Escanea para más info</p>
      </div>
    </div>

    <div class="footer-bottom">
      <p>© 2025 PUMFEST. Todos los derechos reservados.</p>
    </div>
  </footer>

  <script src="../JS/global.js"></script>
  <script src="../JS/JS_Asistente/info-evento.js"></script>
</body>
</html>
