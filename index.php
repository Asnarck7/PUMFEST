<?php
// ✅ Iniciar sesión solo si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// ✅ Conexión a la BD
require_once "PHP/conexion.php";

// ✅ Verificar si el asistente está logueado
$logueado = isset($_SESSION['asistente']);
$nombreUsuario = $logueado ? htmlspecialchars($_SESSION['asistente']['nombre']) : "";

// ✅ Cargar todos los eventos activos
$eventos = [];

$sql = "SELECT * FROM eventos WHERE estado = 'activo' ORDER BY fecha_hora ASC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $eventos[] = $row;
  }
}

// ✅ FUNCIÓN PARA RENDERIZAR TARJETAS DE EVENTOS
function renderEventosPorCategoria($eventos, $categoriaFiltro = null)
{
  foreach ($eventos as $ev) {

    // ✅ Filtrar por categoría
    if ($categoriaFiltro !== null) {
      $catBD = strtolower(trim($ev["categoria"]));
      $catFiltro = strtolower(trim($categoriaFiltro));

      // Aceptar singular y plural
      if ($catBD !== $catFiltro && $catBD !== $catFiltro . 's') {
        continue;
      }
    }


    $id = $ev["evento_id"];
    $titulo = htmlspecialchars($ev["titulo"]);
    $img = "IMG/eventos/" . htmlspecialchars($ev["imagen"]);
    $ciudad = htmlspecialchars($ev["ciudad"]);
    $fecha = date("d M Y - h:i A", strtotime($ev["fecha_hora"]));

    echo "
        <div class='event-card' onclick=\"window.location.href='PHP/info-evento.php?id=$id'\">
            <img src='$img' alt='$titulo'>
            <div class='event-info'>
                <h3>$titulo</h3>
                <p>$fecha | $ciudad</p>
            </div>
        </div>
        ";
  }
}
?>



<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PUMFEST - Tickets para Eventos</title>
  <link rel="icon" type="image/png" href="LogoPUMFEST/LogoPUMFESTsinFondo.png">
  <link rel="stylesheet" href="CSS/Index.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
  <!-- ================== PANTALLA DE CARGA ================== -->
  <div id="loader">
    <div class="loader-logo"></div>
    <div class="loading-bar"></div>
  </div>

  <!-- HEADER -->
  <header class="header">
    <div class="header-top">
      <div class="logo"></div>

      <!-- Video del PUMFEST -->
      <div class="PUMFEST" id="videoOverlay">
        <video id="promoVideo" src="Videos/VideoPUMFEST80Years.mp4" autoplay></video>
      </div>
      <!-- Usuario -->
      <div class="user-menu">
        <button id="userBtn" class="user-icon">
          <img src="LogoPUMFEST/LoginPUMFESTsinfondo.png" alt="Usuario" class="user-img" />

          <span class="login-text">
            <div class="loader-container">
              <div class="flipping-cards">
                <?php
                // Si está logueado, mostrar su nombre animado
                // Si no, mostrar "login"
                $texto = $logueado ? $nombreUsuario : "login";

                // Opcional: si quieres solo el primer nombre (quita esto si no lo deseas)
                $texto = explode(" ", trim($texto))[0];

                // Mostrar cada letra como tarjeta animada
                foreach (str_split($texto) as $letra) {
                  echo '<div class="card">' . htmlspecialchars($letra) . '</div>';
                }
                ?>
              </div>
            </div>
          </span>
        </button>

        <!-- Menú dinámico -->
        <div id="userDropdown" class="user-dropdown">
          <?php if ($logueado): ?>
            <span class="user-name">👋 <?php echo htmlspecialchars($nombreUsuario); ?></span>
            <a href="PHP/PHP_Asistente/perfilAsistente.php"><i class="fa-solid fa-user"></i> Mi perfil</a>
            <a href="PHP/cerrar-sesion.php" class="logout-btn">Cerrar sesión</a>
          <?php else: ?>
            <a href="PHP/PHP_Asistente/iniciar-asistente.php"><i class="fa-solid fa-right-to-bracket"></i> Iniciar
              sesión</a>
            <a href="PHP/PHP_Asistente/crear-asistente.php"><i class="fa-solid fa-user-plus"></i> Crear cuenta</a>
          <?php endif; ?>
        </div>
      </div>

    </div>

    <div class="header-bottom">
      <div class="search-bar">
        <input id="searchInput" type="text" placeholder="Buscar eventos..." />
        <button id="searchButton">🔍</button>
      </div>

      <!-- Categorías -->
      <div class="categories">
        <a href="#destacados"><i class="fa-solid fa-star"></i> Destacados</a>
        <a href="#conciertos"><i class="fa-solid fa-music"></i> Concierto</a>
        <a href="#deporte"><i class="fa-solid fa-football-ball"></i> Deporte</a>
        <a href="#teatro"><i class="fa-solid fa-masks-theater"></i> Teatro</a>
        <a href="#familiar"><i class="fa-solid fa-children"></i> Familiar</a>
        <a href="#festival"><i class="fa-solid fa-campground"></i> Festival</a>
        <a href="#conferencia"><i class="fa-solid fa-microphone"></i> Conferencia</a>
        <a href="#tecnologia"><i class="fa-solid fa-microchip"></i> Tecnología</a>
        <a href="#gastronomia"><i class="fa-solid fa-utensils"></i> Gastronomía</a>
        <a href="#moda"><i class="fa-solid fa-shirt"></i> Moda</a>
      </div>
    </div>
  </header>

  <!-- 🌟 ================= BANNER DINÁMICO CON EFECTO ================= -->
  <div class="banner-slider" id="bannerSlider">
    <canvas id="bannerStars"></canvas>

    <?php
    require_once "PHP/conexion.php";

    $destacados = $conn->query("SELECT * FROM eventos WHERE destacado = 1 AND estado = 'activo' ORDER BY fecha_hora ASC");
    if ($destacados && $destacados->num_rows > 0):
      $isActive = true;
      while ($ev = $destacados->fetch_assoc()):
        $img = "IMG/eventos/" . htmlspecialchars($ev["imagen"]);
        $titulo = htmlspecialchars($ev["titulo"]);
        $id = $ev["evento_id"];
        ?>
        <div class="banner-slide <?= $isActive ? 'active' : '' ?>" style="background-image: url('<?= $img ?>')"
          onclick="window.location.href='PHP/info-evento.php?id=<?= $id ?>'">
          <h2>🎉 <?= $titulo ?> 🎶</h2>
        </div>
        <?php
        $isActive = false;
      endwhile;
    else:
      ?>
      <div class="banner-slide active" style="background-image: url('IMG/nochedeestrella.jpg')">
        <h2>🎵 No hay eventos destacados por el momento 🎵</h2>
      </div>
    <?php endif; ?>
  </div>



  <!-- ===================== -->
  <!-- SECCIONES DE EVENTOS -->
  <!-- ===================== -->
  <!-- ================= DESTACADOS ================= -->
  <section id="destacados">
    <h2 class="section-title">🎟️ Eventos Destacados</h2>
    <div class="event-grid">
      <?php
      $destacados = $conn->query("SELECT * FROM eventos WHERE es_destacado = 1 AND estado = 'activo' ORDER BY fecha_hora ASC");
      if ($destacados && $destacados->num_rows > 0):
        while ($row = $destacados->fetch_assoc()):
          $img = "IMG/eventos/" . htmlspecialchars($row["imagen"]);
          $titulo = htmlspecialchars($row["titulo"]);
          $fecha = date("d M", strtotime($row["fecha_hora"]));
          $lugar = htmlspecialchars($row["lugar"]);
          $id = $row["evento_id"];
          ?>
          <div class="event-card" onclick="window.location.href='PHP/info-evento.php?id=<?= $id ?>'">
            <img src="<?= $img ?>" alt="<?= $titulo ?>">
            <div class="event-info">
              <h3><?= $titulo ?></h3>
              <p><?= $fecha ?> | <?= $lugar ?></p>
            </div>
          </div>
          <?php
        endwhile;
      else:
        echo "<p style='color:white;'>No hay eventos destacados actualmente.</p>";
      endif;
      ?>
    </div>
  </section>



  <!-- ================= CONCIERTOS ================= -->
  <!-- Conciertos -->
  <section id="conciertos">
    <h2 class="section-title">Conciertos</h2>
    <div class="event-grid">
      <?php renderEventosPorCategoria($eventos, "Conciertos"); ?>
    </div>
  </section>


  <!-- Deporte -->
  <section id="deporte">
    <h2 class="section-title">Deporte</h2>
    <div class="event-grid">
      <?php renderEventosPorCategoria($eventos, "Deporte"); ?>
    </div>
  </section>

  <!-- Teatro -->
  <section id="teatro">
    <h2 class="section-title">Teatro</h2>
    <div class="event-grid">
      <?php renderEventosPorCategoria($eventos, "Teatro"); ?>
    </div>
  </section>

  <!-- Familiar -->
  <section id="familiar">
    <h2 class="section-title">Familiar</h2>
    <div class="event-grid">
      <?php renderEventosPorCategoria($eventos, "Familiar"); ?>
    </div>
  </section>

  <!-- Festival -->
  <section id="festival">
    <h2 class="section-title">Festival</h2>
    <div class="event-grid">
      <?php renderEventosPorCategoria($eventos, "Festival"); ?>
    </div>
  </section>

  <!-- Conferencia -->
  <section id="conferencia">
    <h2 class="section-title">Conferencia</h2>
    <div class="event-grid">
      <?php renderEventosPorCategoria($eventos, "Conferencia"); ?>
    </div>
  </section>

  <!-- Tecnología -->
  <section id="tecnologia">
    <h2 class="section-title">Tecnología</h2>
    <div class="event-grid">
      <?php renderEventosPorCategoria($eventos, "Tecnología"); ?>
    </div>
  </section>

  <!-- Gastronomía -->
  <section id="gastronomia">
    <h2 class="section-title">Gastronomía</h2>
    <div class="event-grid">
      <?php renderEventosPorCategoria($eventos, "Gastronomía"); ?>
    </div>
  </section>

  <!-- Moda -->
  <section id="moda">
    <h2 class="section-title">Moda</h2>
    <div class="event-grid">
      <?php renderEventosPorCategoria($eventos, "Moda"); ?>
    </div>
  </section>
  <!-- BOTÓN VOLVER ARRIBA -->
  <div id="animadovolver">
    <img src="LogoPUMFEST/AVATAR_PUMFEST_sinfondo.png" alt="Volver arriba" />
    <p class="text-volver">Volver...</p>
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
        <img src="IMG/qrpumfest.png" alt="QR del sitio" />
        <p>Escanea para más info</p>
      </div>
    </div>

    <div class="footer-bottom">
      <p>© 2025 TuEvento. Todos los derechos reservados.</p>
    </div>
  </footer>
  <script src="JS/Index.js" defer></script>
  <script src="JS/asistente.js"></script>
  <script src="JS/promvideo.js"></script>
  <script src="JS/busquedaEventos.js"></script>

</body>

</html>