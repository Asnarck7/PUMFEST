<?php
// Iniciar sesión solo si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Verificar si el asistente está logueado
$logueado = isset($_SESSION['asistente']);
$nombreUsuario = $logueado ? htmlspecialchars($_SESSION['asistente']['nombre']) : "";
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Info Evento - Jowell & Randy</title>

  <!-- Ícono del sitio -->
  <link rel="icon" type="image/png" href="../LogoPUMFEST/LogoPUMFESTsinFondo.png" />

  <!-- Estilos CSS -->
  <link rel="stylesheet" href="../CSS/info-evento.css" />
  <link rel="stylesheet" href="../CSS/global.css">

  <!-- Iconos -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>

<body>
  <!-- Header -->
  <header class="header">
    <div class="header-top">
      <!-- Logo con enlace al Index -->
      <a href="../PHP/index.php" class="logo-link">
        <div class="logo"></div>
      </a>

      <!-- Menú de asistente (usuario) -->
      <div class="user-menu">
        <button id="userBtn" class="user-icon">
          <img src="../LogoPUMFEST/LoginPUMFESTsinfondo.png" alt="Usuario" class="user-img" />

          <span class="login-text">
            <div class="loader-container">
              <div class="flipping-cards">
                <?php
                // Mostrar nombre si está logueado, o "login" si no
                $texto = $logueado ? $nombreUsuario : "login";
                $texto = explode(" ", trim($texto))[0]; // solo primer nombre opcional
                foreach (str_split($texto) as $letra) {
                  echo '<div class="card">' . htmlspecialchars($letra) . '</div>';
                }
                ?>
              </div>
            </div>
          </span>
        </button>

        <!-- Menú desplegable -->
        <div id="userDropdown" class="user-dropdown">
          <?php if ($logueado): ?>
            <span class="user-name">👋 <?php echo htmlspecialchars($nombreUsuario); ?></span>
            <a href="perfil.php"><i class="fa-solid fa-user"></i> Mi perfil</a>
            <a href="../PHP/cerrar-sesion.php" class="logout-btn">
              <i class="fa-solid fa-right-from-bracket"></i> Cerrar sesión
            </a>
          <?php else: ?>
            <a href="../PHP/iniciar-asistente.php">
              <i class="fa-solid fa-right-to-bracket"></i> Iniciar sesión
            </a>
            <a href="../PHP/crear-asistente.php">
              <i class="fa-solid fa-user-plus"></i> Crear cuenta
            </a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </header>
  <!-- /Header -->

  <!-- Contenido principal -->
  <div class="header-bottom">
    <div class="container">
      <div class="main-content">
        <div class="event-detail">
          <img src="../IMG/prueba1.jpg" alt="Jowell y Randy" class="event-image" />

          <div class="event-text">
            <h1>JOWELL Y RANDY | 3D</h1>
            <div class="dates">
              <div class="date-item">
                <p>
                  <i class="fa-solid fa-calendar-days"></i>
                  <strong>20 Dic</strong> |
                  <i class="fa-solid fa-location-dot"></i>
                  Bogotá | Movistar Arena
                </p>
                <button class="btn"
                  onclick="guardarInfo('Jowell & Randy | 3D', '21 Dic 2025 - Medellín - Plaza Mayor')">
                  <i class="fa-solid fa-ticket"></i> Entradas
                </button>
              </div>

              <div class="date-item">
                <p>
                  <i class="fa-solid fa-calendar-days"></i>
                  <strong>21 Dic</strong> |
                  <i class="fa-solid fa-location-dot"></i>
                  Medellín | Plaza Mayor texto mas grande para la prueba del texto dentro
                </p>
                <button class="btn"
                  onclick="guardarInfo('Jowell & Randy | 3D', '21 Dic 2025 - Medellín - Plaza Mayor')">
                  <i class="fa-solid fa-ticket"></i> Entradas
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
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
      <p>© 2025 TuEvento. Todos los derechos reservados.</p>
    </div>
  </footer>

  <script src="../JS/asistente.js"></script>
  <script src="../JS/global.js"></script>

</body>
</html>