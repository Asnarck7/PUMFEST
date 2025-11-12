<?php
// =============================
// 🔐 Iniciar sesión y conectar a la BD
// =============================
session_start();
require_once "../conexion.php";

// ✅ Verifica si el asistente ha iniciado sesión
if (!isset($_SESSION['asistente'])) {
  header("Location: iniciar-asistente.php");
  exit();
}

// ✅ Obtiene la información del usuario en sesión
$asistente = $_SESSION['asistente'];
$usuario_id = $asistente['id'];

// ✅ Consulta los datos actualizados del usuario
$sql = "SELECT nombre, apellido, email, telefono FROM usuarios WHERE usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Perfil del Asistente</title>
  <link rel="stylesheet" href="../../CSS/CSS_Asistente/perfil.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <!-- ============================= HEADER ============================= -->
  <header class="header">
    <a href="../../index.php?skipVideo=1" class="logo" id="irInicio"></a>
  </header>

  <!-- ============================= CONTENIDO PRINCIPAL ============================= -->
  <main class="perfil-main">
    <div class="perfil-container">
      <h2>Perfil del Asistente</h2>

      <!-- Imagen del avatar -->
      <div class="perfil-avatar">
        <img src="../../LogoPUMFEST/LoginPUMFESTsinfondo.png" alt="Avatar del asistente">
      </div>

      <!-- Información del usuario -->
      <div class="info">
        <p><strong>Nombre:</strong> <?= htmlspecialchars($usuario['nombre']) ?>
          <?= htmlspecialchars($usuario['apellido']) ?>
        </p>
        <p><strong>Correo:</strong> <?= htmlspecialchars($usuario['email']) ?></p>
        <p><strong>Teléfono:</strong> <?= htmlspecialchars($usuario['telefono'] ?? 'No registrado') ?></p>
      </div>

      <!-- Botones principales -->
      <button class="editar-btn" id="abrirEditar">Editar perfil</button>
      <button class="eliminar-btn" id="abrirModal">Borrar cuenta</button>
      <button class="editar-btn" onclick="window.location.href='mis-tickets.php'">🎟️ Ver mis Tickets</button>

    </div>
  </main>

  <!-- ============================= MODAL EDITAR PERFIL ============================= -->
  <div id="modalEditar" class="modal">
    <div class="modal-contenido">
      <span class="cerrar" id="cerrarEditar">&times;</span>
      <h3>Editar perfil</h3>

      <!-- Formulario para editar datos personales -->
      <form action="actualizarPerfil.php" method="POST" class="editar-formulario">
        <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required
          placeholder="Nombre">
        <input type="text" name="apellido" value="<?= htmlspecialchars($usuario['apellido']) ?>" required
          placeholder="Apellido">
        <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required
          placeholder="Correo electrónico">
        <input type="text" name="telefono" value="<?= htmlspecialchars($usuario['telefono'] ?? '') ?>"
          placeholder="Teléfono">

        <button type="submit" class="guardar-btn">Guardar cambios</button>
      </form>

      <!-- 🔐 Botón para cambio de contraseña -->
      <div style="margin-top: 15px; text-align: center;">
        <hr style="margin: 10px 0;">
        <p><strong>¿Deseas cambiar tu contraseña?</strong></p>
        <button id="btnEnviarCodigo" class="editar-btn">Cambiar contraseña</button>
      </div>
    </div>
  </div>

  <!-- ============================= MODAL CAMBIAR CONTRASEÑA ============================= -->
  <div id="modalCodigo" class="modal">
    <div class="modal-contenido">
      <span class="cerrar" id="cerrarCodigo">&times;</span>
      <h3>Verificar código</h3>
      <p>Ingresa el código enviado a tu correo y tu nueva contraseña.</p>

      <!-- ✅ Formulario para cambiar contraseña -->
      <form id="formCambiarContrasena" method="POST" action="cambiarContrasena.php">
        <input type="text" id="codigoVerif" name="codigo" placeholder="Código de 5 dígitos" required>
        <input type="password" id="nuevaContrasena" name="nueva_contrasena" placeholder="Nueva contraseña" required>
        <input type="password" id="confirmarContrasena" name="confirmar_contrasena" placeholder="Confirmar contraseña"
          required>

        <button type="submit" id="btnVerificarCodigo" class="confirmar-btn">Guardar nueva contraseña</button>
      </form>
    </div>
  </div>

  <!-- ============================= MODAL ELIMINAR CUENTA ============================= -->
  <div id="miModal" class="modal">
    <div class="modal-contenido">
      <span class="cerrar" id="cerrarModal">&times;</span>
      <h3>Confirmar eliminación</h3>
      <p>Por favor, ingresa tu contraseña para confirmar:</p>

      <form action="eliminarAsistente.php" method="POST">
        <input type="password" name="password" placeholder="Contraseña" required autocomplete="current-password">
        <button type="submit" class="confirmar-btn">Eliminar cuenta</button>
      </form>

      <?php if (isset($_GET['error'])): ?>
        <p style="color:red; font-size:14px; margin-top:10px;">
          <?= $_GET['error'] === 'contraseña_incorrecta' ? '⚠️ Contraseña incorrecta.' : 'Error al eliminar cuenta.' ?>
        </p>
      <?php endif; ?>
    </div>
  </div>

  <!-- ============================= FOOTER ============================= -->
  <footer class="footer">
    <div class="footer-bottom">
      <p>© 2025 PUMFEST. Todos los derechos reservados.</p>
    </div>
  </footer>

  <!-- ============================= SCRIPTS ============================= -->
  <script src="../../JS/perfil.js"></script>
  <script src="../../JS/JS_Asistente/cambiarContrasena.js"></script>
</body>

</html>