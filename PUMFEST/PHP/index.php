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
  <title>PUMFEST - Tickets para Eventos</title>
  <link rel="icon" type="image/png" href="../LogoPUMFEST/LogoPUMFESTsinFondo.png">
  <link rel="stylesheet" href="../CSS/Index.css" />
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
        <video id="promoVideo" src="../Videos/VideoPUMFEST80Years.mp4" autoplay></video>
      </div>
      <!-- Usuario -->
      <div class="user-menu">
        <button id="userBtn" class="user-icon">
          <img src="../LogoPUMFEST/LoginPUMFESTsinfondo.png" alt="Usuario" class="user-img" />

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
            <a href="perfil.php"><i class="fa-solid fa-user"></i> Mi perfil</a>
            <a href="../PHP/cerrar-sesion.php" class="logout-btn">Cerrar sesión</a>
          <?php else: ?>
            <a href="../PHP/iniciar-asistente.php"><i class="fa-solid fa-right-to-bracket"></i> Iniciar sesión</a>
            <a href="../PHP/crear-asistente.php"><i class="fa-solid fa-user-plus"></i> Crear cuenta</a>
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

  <!-- Banner Slider -->
  <div class="banner-slider">
    <div class="banner-slide active" style="background-image: url('../IMG/nochedeestrella.jpg')">
      <h2> 🎶 Concierto: Noche de Estrellas – 15 Noviembre 🎶 </h2>
    </div>
    <div class="banner-slide" style="background-image: url('../IMG/partido.jpg')">
      <h2>⚽ Partido Final Liga Nacional – 22 Noviembre</h2>
    </div>
    <div class="banner-slide" style="background-image: url('../IMG/teatro.jpg')">
      <h2>🎭 Teatro Central – Obra “Sueños en Escena”</h2>
    </div>
    <div class="banner-slide" style="background-image: url('../IMG/familiar.jpg')">
      <h2>🎡 Festival Familiar de Navidad – 1 Diciembre</h2>
    </div>
  </div>

  <!-- ===================== -->
  <!-- SECCIONES DE EVENTOS -->
  <!-- ===================== -->

  <!-- Destacados -->
  <section id="destacados">
    <h2 class="section-title">🎟️ Eventos Destacados</h2>
    <div class="event-grid">
      <div class="event-card" onclick="window.location.href='info-evento.php'">
        <img src="../IMG/familiar.jpg" alt="Jowell & Randy" />
        <div class="event-info">
          <h3>JOWELL & RANDY | 3D</h3>
          <p>20 de Diciembre | Movistar Arena</p>
        </div>
      </div>

      <div class="event-card" onclick="window.location.href='info-evento.php'">
        <img src="../IMG/nochedeestrella.jpg" alt="Karol G" />
        <div class="event-info">
          <h3>KAROL G | MAÑANA SERÁ BONITO TOUR</h3>
          <p>10 de Noviembre | Estadio El Campín</p>
        </div>
      </div>

      <div class="event-card" onclick="window.location.href='info-evento.php'">
        <img src="../IMG/teatro.jpg" alt="Morat" />
        <div class="event-info">
          <h3>MORAT | LOS COLOMBIANOS</h3>
          <p>3 de Diciembre | Coliseo Live</p>
        </div>
      </div>

      <div class="event-card" onclick="window.location.href='info-evento.php'">
        <img src="../IMG/teatro.jpg" alt="Teatro Broadway" />
        <div class="event-info">
          <h3>EL REY LEÓN | MUSICAL</h3>
          <p>25 de Octubre | Teatro Mayor</p>
        </div>
      </div>

      <div class="event-card" onclick="window.location.href='info-evento.php'">
        <img src="../IMG/nochedeestrella.jpg" alt="Festival Vallenato" />
        <div class="event-info">
          <h3>FESTIVAL VALLENATO</h3>
          <p>1 de Mayo | Valledupar</p>
        </div>
      </div>

      <div class="event-card" onclick="window.location.href='info-evento.php'">
        <img src="../IMG/partido.jpg" alt="Carrera de Colores" />
        <div class="event-info">
          <h3>CARRERA DE COLORES</h3>
          <p>15 de Enero | Parque Simón Bolívar</p>
        </div>
      </div>
    </div>
  </section>


  <!-- Conciertos -->
  <section id="conciertos">
    <h2 class="section-title">Conciertos</h2>
    <div class="event-grid">
      <div class="event-card" onclick="window.location.href='info-evento.php'">
        <img src="../IMG/nochedeestrella.jpg" alt="Carrera de Colores" />
        <div class="event-info">
          <h3>BOGOTÁ CONCERT</h3>
          <p>Octubre 2025</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Deporte -->
  <section id="deporte">
    <h2 class="section-title">Deporte</h2>
    <div class="event-grid">
      <div class="event-card" onclick="window.location.href='info-evento.php'">
        <img src="../IMG/partido.jpg" alt="Copa Nacional" />
        <div class="event-info">
          <h3>Copa Nacional</h3>
          <p>Noviembre 2025</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Teatro -->
  <section id="teatro">
    <h2 class="section-title">Teatro</h2>
    <div class="event-grid">
      <div class="event-card" onclick="window.location.href='info-evento.php'">
        <img src="../IMG/teatro.jpg" alt="Obra Dramática" />
        <div class="event-info">
          <h3>La Vida en Escena</h3>
          <p>14 de Octubre 2025</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Familiar -->
  <section id="familiar">
    <h2 class="section-title">Familiar</h2>
    <div class="event-grid">
      <div class="event-card" onclick="window.location.href='info-evento.php'">
        <img src="../IMG/familiar.jpg" alt="Anime Tour" />
        <div class="event-info">
          <h3>ANIME TOUR</h3>
          <p>1 de Octubre 2025</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Festival -->
  <section id="festival">
    <h2 class="section-title">Festival</h2>
    <div class="event-grid">
      <div class="event-card" onclick="window.location.href='info-evento.php'">
        <img src="../IMG/nochedeestrella.jpg" alt="Festival de Verano" />
        <div class="event-info">
          <h3>Festival de Verano</h3>
          <p>Julio 2025</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Conferencia -->
  <section id="conferencia">
    <h2 class="section-title">Conferencia</h2>
    <div class="event-grid">
      <div class="event-card" onclick="window.location.href='info-evento.php'">
        <img src="../IMG/familiar.jpg" alt="Charla de Innovación" />
        <div class="event-info">
          <h3>Innovación Empresarial</h3>
          <p>Marzo 2025</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Tecnología -->
  <section id="tecnologia">
    <h2 class="section-title">Tecnología</h2>
    <div class="event-grid">
      <div class="event-card" onclick="window.location.href='info-evento.php'">
        <img src="../IMG/teatro.jpg" alt="ExpoTech" />
        <div class="event-info">
          <h3>ExpoTech 2025</h3>
          <p>Junio 2025</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Gastronomía -->
  <section id="gastronomia">
    <h2 class="section-title">Gastronomía</h2>
    <div class="event-grid">
      <div class="event-card" onclick="window.location.href='info-evento.php'">
        <img src="../IMG/familiar.jpg" alt="Sabores del Mundo" />
        <div class="event-info">
          <h3>Sabores del Mundo</h3>
          <p>Agosto 2025</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Moda -->
  <section id="moda">
    <h2 class="section-title">Moda</h2>
    <div class="event-grid">
      <div class="event-card" onclick="window.location.href='info-evento.php'">
        <img src="../IMG/teatro.jpg" alt="Desfile Fashion Week" />
        <div class="event-info">
          <h3>Colombia Fashion Week</h3>
          <p>Septiembre 2025</p>
        </div>
      </div>
    </div>
  </section>
  <!-- BOTÓN VOLVER ARRIBA -->
  <div id="animadovolver">
    <img src="../LogoPUMFEST/AVATAR_PUMFEST_sinfondo.png" alt="Volver arriba" />
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
  <script src="../JS/Index.js" defer></script>
  <script src="../JS/asistente.js"></script>
  <script src="../JS/promvideo.js"></script>
  <script src="../JS/busquedaEventos.js"></script>

</body>
</html>