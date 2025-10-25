<?php
session_start();

// ✅ Verificar que el asistente haya iniciado sesión
if (!isset($_SESSION['asistente'])) {
    header("Location: iniciar-asistente.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Entradas y Precio - PUMFEST</title>

    <!-- Ícono del sitio -->
    <link
      rel="icon"
      type="image/png"
      href="../LogoPUMFEST/LogoPUMFESTsinFondo.png"
    />

    <!-- Font Awesome -->
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    />

    <!-- Archivo CSS externo -->
    <link rel="stylesheet" href="../CSS/entradas-precio.css" />
    <link rel="stylesheet" href="../CSS/global.css">
  </head>

  <body>
    <!-- HEADER -->
    <header class="header">
      <div class="header-top">
        <!-- LOGO -->
        <a href="../PHP/index.php" class="logo-link">
          <div class="logo"></div>
        </a>
      </div>
    </header>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="container">
      <h1 id="evento-nombre">Entradas y Precio</h1>
      <p id="evento-fecha"></p>

      <div class="layout">
        <!-- Imagen del mapa -->
        <div class="mapa">
          <img src="../IMG/escenario.png" alt="Plano del evento" />
        </div>

        <!-- Lista de zonas -->
        <div class="precios">
          <h2 class="titulo-zonas">
            <i class="fa-solid fa-ticket"></i> Zonas Disponibles
          </h2>

          <div class="precio-item" onclick="seleccionarZona('A', 150000)">
            <span><i class="fa-solid fa-star"></i> Zona A</span>
            <span class="precio">$150.000</span>
          </div>

          <div class="precio-item" onclick="seleccionarZona('B', 100000)">
            <span><i class="fa-solid fa-music"></i> Zona B</span>
            <span class="precio">$100.000</span>
          </div>

          <div class="precio-item" onclick="seleccionarZona('C', 70000)">
            <span><i class="fa-solid fa-users"></i> Zona C</span>
            <span class="precio">$70.000</span>
          </div>
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

      <!-- Botón comprar -->
      <button class="comprar" type="button" onclick="guardarCompra()">
        <i class="fa-solid fa-cart-plus"></i> Agregar al Carrito
      </button>

      <!-- 💡 Imagen de avatar debajo del botón -->
      <div class="avatar-container">
        <img
          src="../LogoPUMFEST/CarritoCompras.png"
          alt="Avatar de usuario"
          class="avatarTicket"
        />
      </div>
    </main>

    <!-- Notificación flotante -->
    <div id="notificacion" class="notificacion">
      <p id="mensaje"></p>
      <img
        src="../LogoPUMFEST/advertenciaPUMFEST.png"
        alt="Avatar"
        class="avatar"
      />
    </div>

    <!-- FOOTER -->
    <footer class="footer">
      <div class="footer-content">
        <!-- Categorías -->
        <div class="footer-section">
          <h4><i class="fa-solid fa-layer-group"></i> Categorías</h4>
          <a href="#"><i class="fa-solid fa-music"></i> Conciertos</a>
          <a href="#"><i class="fa-solid fa-masks-theater"></i> Teatros</a>
          <a href="#"><i class="fa-solid fa-futbol"></i> Deporte</a>
          <a href="#"><i class="fa-solid fa-children"></i> Familia</a>
          <a href="#"><i class="fa-solid fa-comments"></i> Foros</a>
          <a href="#"><i class="fa-solid fa-star"></i> Experiencias</a>
        </div>

        <!-- Sobre nosotros -->
        <div class="footer-section">
          <h4><i class="fa-solid fa-circle-info"></i> Sobre Nosotros</h4>
          <a href="#"><i class="fa-solid fa-blog"></i> Blog</a>
          <a href="#"><i class="fa-solid fa-briefcase"></i> Carreras</a>
          <a href="#"><i class="fa-solid fa-ticket"></i> Entradas para tu evento</a>
          <a href="#"><i class="fa-solid fa-file-contract"></i> Términos de venta</a>
        </div>

        <!-- Redes Sociales -->
        <div class="footer-section">
          <h4><i class="fa-solid fa-hashtag"></i> Redes Sociales</h4>
          <a href="#"><i class="fa-brands fa-facebook"></i> Facebook</a>
          <a href="#"><i class="fa-brands fa-instagram"></i> Instagram</a>
          <a href="#"><i class="fa-brands fa-x-twitter"></i> X (Twitter)</a>
        </div>

        <!-- QR -->
        <div class="footer-qr">
          <img src="../IMG/qrpumfest.png" alt="QR del sitio" />
          <p>Escanea para más info</p>
        </div>
      </div>

      <div class="footer-bottom">
        <p>© 2025 PUMFEST. Todos los derechos reservados.</p>
      </div>
    </footer>

    <!-- Scripts -->
    <script src="../JS/asistente.js"></script>
    <script src="../JS/entradas.js"></script>
    <script src="../JS/global.js"></script>
  </body>
</html>
