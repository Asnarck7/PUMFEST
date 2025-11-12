<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Comprar Ticket</title>
  <link rel="icon" type="image/png" href="../../LogoPUMFEST/LogoPUMFESTsinFondo.png" />
  <link rel="stylesheet" href="../../CSS/CSS_Asistente/comprar-ticket.css" />
  <link rel="stylesheet" href="../../CSS/global.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
  <header class="header">
    <div class="header-top">
      <a href="../../index.php" class="logo-link"><div class="logo"></div></a>
    </div>
  </header>

  <div class="container">
    <h1>Confirmar Compra</h1>
    <div class="resumen">
      <div class="ticket">
        <img src="../../IMG/escenario.png" alt="Ticket" />
        <div class="detalles">
          <p><strong>Evento:</strong> <span id="evento"></span></p>
          <p><strong>Fecha:</strong> <span id="fecha"></span></p>
          <p><strong>Zona:</strong> <span id="zona"></span></p>
          <p><strong>Cantidad:</strong> <span id="cantidad"></span></p>
          <p><strong>Total:</strong> <span id="total"></span></p>
        </div>
      </div>

      <button class="finalizar" onclick="finalizarCompra()">Comprar Ahora</button>

      <div class="avatar-container">
        <img src="../../LogoPUMFEST/PumFestConTICKETS.png" alt="Avatar" class="avatarTicket" />
      </div>
    </div>
  </div>

  <footer class="footer">
    <div class="footer-bottom">
      <p>© 2025 PUMFEST. Todos los derechos reservados.</p>
    </div>
  </footer>

  <!-- JS -->
  <script src="../../JS/comprarTicket.js"></script>
  <script src="../../JS/asistente.js"></script>
  <script src="../../JS/global.js"></script>
</body>
</html>
