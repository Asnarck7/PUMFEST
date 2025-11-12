<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Iniciar Sesión</title>

  <!-- Ícono del sitio -->
  <link rel="icon" type="image/png" href="../../LogoPUMFEST/LogoPUMFESTsinFondo.png">

  <!-- Estilos CSS -->
  <link rel="stylesheet" href="../../CSS/iniciar-sesion.css" />
  <link rel="stylesheet" href="../../CSS/global.css">
</head>

<body>
  <!-- ================================
         HEADER
    ================================= -->
  <header class="header">
    <div class="header-top">
      <div class="logo" onclick="window.location.href='../../index.php'"></div>
    </div>
  </header>

  <!-- 🌟 Alerta de Sesión Incorrecta (con animación suave) -->
  <div id="alerta" class="alerta-oculta">
    <div class="alerta-contenido">
      <img src="../../LogoPUMFEST/SesionIncorrectoPUMFEST.png" alt="Error" class="alerta-img" />
      <p id="alerta-texto">Correo o contraseña incorrectos</p>
      <button id="cerrarAlerta">Entendido</button>
    </div>
  </div>


  <!-- Pantalla de carga -->
  <div id="loader" class="loader-oculto">
    <div class="cubes-loader">
      <div class="📦"></div>
      <div class="📦"></div>
      <div class="📦"></div>
      <div class="📦"></div>
      <div class="📦"></div>
    </div>
    <p>Iniciando sesión...</p>
  </div>


  <!-- ================================
         FORMULARIO DE INICIO DE SESIÓN
    ================================= -->
  <main class="container">
    <h1>Iniciar Sesión</h1>

    <!-- Imagen dentro del formulario -->
    <div class="login-image">
      <img src="../../LogoPUMFEST/LoginPUMFESTsinfondo.png" alt="Logo PUMFEST">
    </div>

    <form action="../PHP_Asistente/loginAsistente.php" method="POST" class="login-form">
      <div class="form-group">
        <label for="email">Correo electrónico</label>
        <input type="email" id="email" name="email" placeholder="Ingresa tu correo" required />
      </div>

      <div class="form-group">
        <label for="password">Contraseña</label>
        <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña" required />
      </div>

      <button type="submit" class="btn-login">Entrar</button>

      <p class="register-link">
        ¿No tienes cuenta? <a href="../PHP_Asistente/crear-asistente.php">Regístrate aquí</a>
      </p>
    </form>
  </main>

  <!-- ================================
         FOOTER
    ================================= -->
  <footer class="footer">
    <div class="footer-bottom">
      <p>© 2025 PUMFEST. Todos los derechos reservados.</p>
    </div>
  </footer>

  <script src="../../JS/asistente.js"></script>
  <script src="../../JS/iniciar-sesion.js"></script>
  <script src="../../JS/global.js"></script>

</body>

</html>