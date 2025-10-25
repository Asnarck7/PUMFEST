<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Comprar Ticket</title>
    <link
      rel="icon"
      type="image/png"
      href="../LogoPUMFEST/LogoPUMFESTsinFondo.png"
    />
    <link rel="stylesheet" href="../CSS/comprar-ticket.css" />
    <link rel="stylesheet" href="../CSS/global.css">
  </head>

  <body>
    <!-- Header -->
    <header class="header">
      <div class="header-top">
        <!-- 🔗 Enlace que lleva al index -->
        <a href="../PHP/index.php" class="logo-link">
          <div class="logo"></div>
        </a>
      </div>
    </header>
    <!-- termina Header -->

    <div class="container">
      <h1>Confirmar Compra</h1>
      <div class="resumen">
        <div class="ticket">
          <img src="../IMG/escenario.png" alt="Ticket" />
          <div class="detalles">
            <p><strong>Evento:</strong> <span id="evento"></span></p>
            <p><strong>Fecha:</strong> <span id="fecha"></span></p>
            <p><strong>Zona:</strong> <span id="zona"></span></p>
            <p><strong>Cantidad:</strong> <span id="cantidad"></span></p>
            <p><strong>Total:</strong> <span id="total"></span></p>
          </div>
        </div>
        <button class="finalizar" onclick="finalizarCompra()">
          Comprar Ahora
        </button>

        <!-- 💡 Imagen de avatar debajo del botón -->
        <div class="avatar-container">
          <!-- 👉 Puedes cambiar el nombre o la ruta de la imagen -->
          <img
            src="../LogoPUMFEST/PumFestConTICKETS.png"
            alt="Avatar de usuario"
            class="avatarTicket"
          />
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
          <a href="#"
            ><i class="fa-solid fa-ticket"></i> Entradas para tu evento</a
          >
          <a href="#"
            ><i class="fa-solid fa-file-contract"></i> Términos de venta</a
          >
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

    <!-- Scripts -->
    <script src="../JS/comprarTicket.js"></script>
    <script src="../JS/asistente.js."></script>
    <script src="../JS/global.js"></script>
  </body>
</html>
