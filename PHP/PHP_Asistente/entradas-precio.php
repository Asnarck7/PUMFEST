<?php
session_start();
require_once "../conexion.php";

// ✅ Verificar que el asistente haya iniciado sesión
if (!isset($_SESSION['asistente'])) {
  header("Location: iniciar-asistente.php");
  exit;
}

// ✅ Obtener el ID del evento desde la URL
$evento_id = $_GET['id'] ?? null;

if (!$evento_id) {
  echo "<p>Error: ID de evento no proporcionado.</p>";
  exit;
}

// ✅ Consultar información del evento
$stmt = $conn->prepare("SELECT * FROM eventos WHERE evento_id = ?");
$stmt->bind_param("i", $evento_id);
$stmt->execute();
$evento = $stmt->get_result()->fetch_assoc();

if (!$evento) {
  echo "<p>Error: Evento no encontrado.</p>";
  exit;
}

// ✅ Consultar las categorías (zonas de entradas)
$stmt2 = $conn->prepare("SELECT * FROM categorias_entrada WHERE evento_id = ?");
$stmt2->bind_param("i", $evento_id);
$stmt2->execute();
$categorias = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($evento['titulo']) ?> - Entradas y Precio</title>

  <!-- Ícono -->
  <link rel="icon" type="image/png" href="../../LogoPUMFEST/LogoPUMFESTsinFondo.png" />

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <!-- CSS -->
  <link rel="stylesheet" href="../../CSS/CSS_Asistente/entradas-precio.css" />
  <link rel="stylesheet" href="../../CSS/global.css">
</head>
<body>
  <!-- HEADER -->
  <header class="header">
    <div class="header-top">
      <a href="../../index.php" class="logo-link">
        <div class="logo"></div>
      </a>
    </div>
  </header>

  <!-- CONTENIDO PRINCIPAL -->
  <main class="container">
    <h1 id="evento-nombre"><?= htmlspecialchars($evento['titulo']) ?></h1>
    <p id="evento-fecha">
      <?= date("d M Y, H:i", strtotime($evento['fecha_hora'])) ?> | <?= htmlspecialchars($evento['ciudad']) ?>
    </p>

    <div class="layout">
      <!-- Imagen -->
      <div class="mapa">
        <img src="../../IMG/escenario.png" alt="Plano del evento" />
      </div>

      <!-- Zonas disponibles -->
      <div class="precios">
        <h2 class="titulo-zonas">
          <i class="fa-solid fa-ticket"></i> Zonas Disponibles
        </h2>

        <?php if (!empty($categorias)): ?>
          <?php foreach ($categorias as $cat): ?>
            <div 
              class="precio-item" 
              data-categoria-id="<?= $cat['categoria_id'] ?>" 
              onclick="seleccionarZona('<?= htmlspecialchars($cat['nombre']) ?>', <?= intval($cat['precio']) ?>, <?= $cat['categoria_id'] ?>)"
            >
              <span><i class="fa-solid fa-star"></i> <?= htmlspecialchars($cat['nombre']) ?></span>
              <span class="precio">$<?= number_format($cat['precio'], 0, ',', '.') ?></span>
              <small class="disponibles"><?= intval($cat['cantidad_disponible']) ?> disponibles</small>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p>No hay categorías disponibles para este evento.</p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Selector de cantidad -->
    <div class="cantidad">
      <button type="button" onclick="cambiar(-1)">−</button>
      <input type="number" id="qty" value="1" min="1" max="5" readonly />
      <button type="button" onclick="cambiar(1)">+</button>
    </div>

    <!-- Total -->
    <h2 id="total" class="total">Total: $0</h2>

    <!-- Botón para continuar -->
    <button class="comprar" type="button" onclick="guardarCompra()">
      <i class="fa-solid fa-cart-plus"></i> Continuar con la compra
    </button>

    <!-- Imagen de avatar -->
    <div class="avatar-container">
      <img src="../../LogoPUMFEST/CarritoCompras.png" alt="Avatar de usuario" class="avatarTicket" />
    </div>
  </main>

  <!-- Notificación -->
  <div id="notificacion" class="notificacion">
    <p id="mensaje"></p>
    <img src="../../LogoPUMFEST/advertenciaPUMFEST.png" alt="Avatar" class="avatar" />
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
        <img src="../../IMG/qrpumfest.png" alt="QR del sitio" />
        <p>Escanea para más info</p>
      </div>
    </div>

    <div class="footer-bottom">
      <p>© 2025 PUMFEST. Todos los derechos reservados.</p>
    </div>
  </footer>

  <!-- Scripts -->
  <script>
    const eventoId = <?= intval($evento_id) ?>;
  </script>
  <script src="../../JS/asistente.js"></script>
  <script src="../../JS/entradas.js"></script>
  <script src="../../JS/global.js"></script>
</body>
</html>