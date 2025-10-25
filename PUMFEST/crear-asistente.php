<?php
// ===============================
// 🧩 CREAR CUENTA (ASISTENTE)
// ===============================

// Mostrar todos los errores (solo en desarrollo)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once "conexion.php";

// ✅ Verificar conexión
if (!$conn || $conn->connect_error) {
  die("<p style='color:red'>❌ Error de conexión a la base de datos: " . $conn->connect_error . "</p>");
}

$error = "";
$nombre = $apellido = $sexo = $telefono = $fecha_nacimiento = $correo = "";

if (isset($_SESSION['asistente'])) {
  header("Location: index.php");
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre = trim($_POST['nombre'] ?? '');
  $apellido = trim($_POST['apellido'] ?? '');
  $sexo = trim($_POST['sexo'] ?? '');
  $telefono = trim($_POST['telefono'] ?? '');
  $fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
  $correo = trim($_POST['correo'] ?? '');
  $confirmar_correo = trim($_POST['confirmar_correo'] ?? '');
  $contrasena = $_POST['contrasena'] ?? '';
  $confirmar = $_POST['confirmar'] ?? '';
  $terminos = isset($_POST['terminos']);

  // ===============================
  // 🧩 VALIDACIONES
  // ===============================
  if (!$terminos) {
    $error = "Debes aceptar los Términos y Condiciones.";
  } elseif (empty($nombre) || empty($apellido) || empty($correo) || empty($contrasena)) {
    $error = "Por favor completa los campos obligatorios.";
  } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $error = "Correo inválido.";
  } elseif ($correo !== $confirmar_correo) {
    $error = "Los correos no coinciden.";
  } elseif ($contrasena !== $confirmar) {
    $error = "Las contraseñas no coinciden.";
  } else {
    // ===============================
    // 🧩 VERIFICAR SI EL CORREO YA EXISTE
    // ===============================
    $stmt = $conn->prepare("SELECT usuario_id FROM usuarios WHERE email = ?");
    if (!$stmt) {
      die("Error en la preparación de la consulta SELECT: " . $conn->error);
    }
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      $error = "Ya existe una cuenta con ese correo.";
      $stmt->close();
    } else {
      $stmt->close();

      // ===============================
      // 🧩 INSERTAR EN USUARIOS
      // ===============================
      $hash = password_hash($contrasena, PASSWORD_DEFAULT);
      $rol = 'asistente';

      $insertUsuario = $conn->prepare("
        INSERT INTO usuarios (nombre, apellido, email, telefono, password, rol, fecha_registro)
        VALUES (?, ?, ?, ?, ?, ?, NOW())
      ");

      if (!$insertUsuario) {
        die("Error preparando INSERT en usuarios: " . $conn->error);
      }

      $insertUsuario->bind_param("ssssss", $nombre, $apellido, $correo, $telefono, $hash, $rol);

      if ($insertUsuario->execute()) {
        $usuario_id = $insertUsuario->insert_id;
        $insertUsuario->close();

        // ===============================
        // 🧩 INSERTAR EN ASISTENTES
        // ===============================
        $insertAsistente = $conn->prepare("
          INSERT INTO asistentes (usuario_id, preferencias) VALUES (?, NULL)
        ");

        if (!$insertAsistente) {
          die("Error preparando INSERT en asistentes: " . $conn->error);
        }

        $insertAsistente->bind_param("i", $usuario_id);
        $insertAsistente->execute();
        $insertAsistente->close();

        // ===============================
        // 🧩 CREAR SESIÓN Y REDIRIGIR
        // ===============================
        $_SESSION['asistente'] = [
          'id' => $usuario_id,
          'nombre' => $nombre,
          'correo' => $correo,
          'rol' => $rol
        ];

        header("Location: index.php");
        exit;

      } else {
        $error = "Error al registrar el usuario: " . $insertUsuario->error;
      }
    }
  }
}
?>

<?php if (!empty($error)): ?>
  <div class="error-box" style="color:red; font-weight:bold;">
    <?php echo htmlspecialchars($error); ?>
  </div>
<?php endif; ?>


<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Crear Cuenta</title>
  <link rel="icon" type="image/png" href="../LogoPUMFEST/LogoPUMFESTsinFondo.png" />
  <link rel="stylesheet" href="../CSS/crear-cuenta.css" />
  <link rel="stylesheet" href="../CSS/global.css">
</head>

<body>
  <!-- ============================= HEADER ============================= -->
  <header class="header">
    <div class="header-top">
      <div class="logo" onclick="window.location.href='../PHP/index.php'"></div>
    </div>
  </header>

  <!-- ============================= FORMULARIO ============================= -->
  <main class="contenido">
    <div class="form-box">
      <div class="form-header">
        <h1>Crear Cuenta</h1>
        <p>Completa los datos para registrarte en PUMFEST como asistente.</p>
      </div>

      <div class="form-image">
        <img src="../LogoPUMFEST/crearCUENTAPumFest.png" alt="Crear Cuenta PUMFEST">
      </div>

      <form action="crear-asistente.php" method="POST">

        <!-- Nombre y Apellido -->
        <div class="input-group">
          <label for="nombre">Nombre</label>
          <input type="text" id="nombre" name="nombre" placeholder="Ingresa tu nombre" required>
        </div>

        <div class="input-group">
          <label for="apellido">Apellido</label>
          <input type="text" id="apellido" name="apellido" placeholder="Ingresa tu apellido" required>
        </div>

        <!-- Sexo -->
        <div class="input-group">
          <label for="sexo">Sexo</label>
          <select id="sexo" name="sexo" required>
            <option value="">Selecciona</option>
            <option value="M">Masculino</option>
            <option value="F">Femenino</option>
            <option value="O">Otro</option>
          </select>
        </div>

        <!-- Teléfono -->
        <div class="input-group">
          <label for="telefono">Teléfono</label>
          <input type="tel" id="telefono" name="telefono" placeholder="+57 300 000 0000"
            pattern="^\+57\s\d{3}\s\d{3}\s\d{4}$" title="Formato válido: +57 300 000 0000" required>
        </div>

        <!-- Fecha de nacimiento -->
        <div class="input-group">
          <label for="fecha_nacimiento">Fecha de nacimiento</label>
          <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required>
        </div>

        <!-- Correo y Confirmación -->
        <div class="input-group">
          <label for="correo">Correo electrónico</label>
          <input type="email" id="correo" name="correo" placeholder="ejemplo@correo.com" required>
        </div>

        <div class="input-group">
          <label for="confirmar_correo">Confirmar correo</label>
          <input type="email" id="confirmar_correo" name="confirmar_correo" placeholder="Repite tu correo" required>
        </div>

        <!-- Contraseña y Confirmación -->
        <div class="input-group">
          <label for="contrasena">Contraseña</label>
          <input type="password" id="contrasena" name="contrasena" placeholder="Crea una contraseña" required>
        </div>

        <div class="input-group">
          <label for="confirmar">Confirmar contraseña</label>
          <input type="password" id="confirmar" name="confirmar" placeholder="Repite tu contraseña" required>
        </div>

        <!-- Aceptar términos -->
        <div class="input-group checkbox-group">
          <input type="checkbox" id="terminos" name="terminos" required>
          <label for="terminos">
            Acepto los
            <span class="link-terminos" id="abrirTerminos">Términos y condiciones</span>
          </label>
        </div>


        <!-- Botón Crear cuenta -->
        <button type="submit" class="btn-crear">Crear cuenta</button>
      </form>

      <p class="texto-login">
        ¿Ya tienes una cuenta? <a href="../PHP/iniciar-asistente.php">Inicia sesión aquí</a>
      </p>
    </div>

<!-- ===================== MODAL DE TÉRMINOS ===================== -->
<div id="modal-terminos" class="modal">
  <div class="modal-content">
    <span class="cerrar-modal" id="cerrarModal">&times;</span>
    <h2>Términos y Condiciones</h2>

    <div class="modal-text">
      <p>
        Bienvenido a <strong>PUMFEST</strong>. Antes de continuar con tu registro o compra de entradas,
        por favor revisa los siguientes términos:
      </p>
      <ul>
        <li>🎟️ Todas las entradas adquiridas en nuestro sitio son personales e intransferibles.</li>
        <li>💳 Una vez completada la compra, no se permiten devoluciones ni reembolsos, salvo cancelación del evento.</li>
        <li>📧 Aceptas recibir confirmaciones, recordatorios y actualizaciones del evento a través de correo electrónico.</li>
        <li>🚫 PUMFEST no se responsabiliza por la reventa no autorizada de entradas fuera de la plataforma oficial.</li>
        <li>🔐 La información que compartes será utilizada únicamente para fines relacionados con el evento.</li>
      </ul>
      <p>Al hacer clic en “Aceptar”, confirmas que has leído y comprendido nuestros términos.</p>
    </div>

    <button id="aceptarTerminos" class="btn-crear">
      Aceptar
      <span class="checkmark"></span>
    </button>
  </div>
</div>


  </main>

  <!-- ============================= FOOTER ============================= -->
  <footer class="footer">
    <div class="footer-bottom">
      <p>© 2025 PUMFEST. Todos los derechos reservados.</p>
    </div>
  </footer>

  <script src="../JS/crearCuenta.js" defer></script>
  <script src="../JS/global.js"></script>
</body>

</html>