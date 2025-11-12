<?php
session_start();
require_once "../conexion.php";

// 🧩 Mostrar errores (solo para desarrollo)
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $telefono = trim($_POST['telefono']);
    $correo = trim($_POST['correo']);
    $confirmar_correo = trim($_POST['confirmar_correo']);
    $password = $_POST['password'];
    $confirmar = $_POST['confirmar'];
    $biografia = trim($_POST['biografia']);
    $terminos = isset($_POST['terminos']);

    // 🔎 Validaciones básicas
    if (!$terminos) {
        $error = "Debes aceptar los Términos y Condiciones.";
    } elseif (empty($nombre) || empty($apellido) || empty($correo) || empty($password)) {
        $error = "Por favor completa los campos obligatorios.";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $error = "Correo electrónico inválido.";
    } elseif ($correo !== $confirmar_correo) {
        $error = "Los correos no coinciden.";
    } elseif ($password !== $confirmar) {
        $error = "Las contraseñas no coinciden.";
    } else {
// 🧩 Verificar si el correo ya existe
$check = $conn->prepare("SELECT usuario_id FROM usuarios WHERE email = ?");
$check->bind_param("s", $correo);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "<script>alert('⚠️ Este correo ya está registrado.'); window.history.back();</script>";
    exit();
}
$check->close();

// ✅ Generar hash de la contraseña
$hash = password_hash($password, PASSWORD_DEFAULT);

// ✅ Rol del organizador
$rol = "organizador";

// 🧩 Insertar en usuarios
$stmt = $conn->prepare("
    INSERT INTO usuarios (nombre, apellido, email, telefono, password, rol, fecha_registro, email_verificado)
    VALUES (?, ?, ?, ?, ?, ?, NOW(), 0)
");
$stmt->bind_param("ssssss", $nombre, $apellido, $correo, $telefono, $hash, $rol);
$stmt->execute();
$usuario_id = $stmt->insert_id;
$stmt->close();

// 🧩 Insertar en organizadores
$stmt2 = $conn->prepare("INSERT INTO organizadores (usuario_id, biografia, verificado, verificado_por_admin) VALUES (?, ?, 0, NULL)");

$stmt2->bind_param("is", $usuario_id, $biografia);
$stmt2->execute();

// ✅ Guardar sesión temporal para verificar correo del organizador
$_SESSION['verificar_correo'] = [
    "id" => $usuario_id,
    "correo" => $correo,
    "nombre" => $nombre,
    "rol" => "organizador"
];

// ✅ Redirigir a la página para ingresar el código
echo "<script>
    alert('✅ Registro exitoso. Ahora debes verificar tu correo antes de continuar.');
    window.location.href='verificarCorreoOrganizador.php';
</script>";
exit;


    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear Organizador | PUMFEST</title>
    <link rel="icon" type="image/png" href="../../LogoPUMFEST/LogoPUMFESTsinFondo.png">
    <link rel="stylesheet" href="../../CSS/crear-cuenta.css">
    <link rel="stylesheet" href="../../CSS/global.css">
</head>

<body>
    <!-- ============================= HEADER ============================= -->
    <header class="header">
        <div class="header-top">
            <div class="logo" onclick="window.location.href='OrganizadorIndex.php'"></div>
        </div>
    </header>

    <!-- ============================= FORMULARIO ============================= -->
    <main class="contenido">
        <div class="form-box">
            <div class="form-header">
                <h1>Crear Cuenta de Organizador</h1>
                <p>Completa los datos para registrarte en PUMFEST como organizador de eventos.</p>
            </div>

            <div class="form-image">
                <img src="../../LogoPUMFEST/crearCUENTAPumFest.png" alt="Crear Organizador PUMFEST">
            </div>

            <?php if (!empty($error)): ?>
                <div class="error-box" style="color:red; font-weight:bold;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <!-- 🔹 Datos personales -->
                <div class="input-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" id="nombre" name="nombre" placeholder="Ej: Laura" required>
                </div>

                <div class="input-group">
                    <label for="apellido">Apellido</label>
                    <input type="text" id="apellido" name="apellido" placeholder="Ej: Torres" required>
                </div>

                <!-- 🔹 Contacto -->
                <div class="input-group">
                    <label for="telefono">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono" placeholder="+57 300 000 0000"
                        pattern="^\+57\s\d{3}\s\d{3}\s\d{4}$" title="Formato válido: +57 300 000 0000" required>
                </div>

                <!-- 🔹 Correo -->
                <div class="input-group">
                    <label for="correo">Correo electrónico</label>
                    <input type="email" id="correo" name="correo" placeholder="ejemplo@correo.com" required>
                </div>

                <div class="input-group">
                    <label for="confirmar_correo">Confirmar correo</label>
                    <input type="email" id="confirmar_correo" name="confirmar_correo" placeholder="Repite tu correo"
                        required>
                </div>

                <!-- 🔹 Biografía -->
                <div class="input-group">
                    <label for="biografia">Biografía o Descripción</label>
                    <textarea id="biografia" name="biografia" rows="4"
                        placeholder="Describe tu experiencia o los eventos que planeas organizar."></textarea>
                </div>

                <!-- 🔹 Contraseña -->
                <div class="input-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" placeholder="Crea una contraseña segura"
                        required>
                </div>

                <div class="input-group">
                    <label for="confirmar">Confirmar contraseña</label>
                    <input type="password" id="confirmar" name="confirmar" placeholder="Repite tu contraseña" required>
                </div>

                <!-- 🔹 Términos -->
                <div class="input-group checkbox-group">
                    <input type="checkbox" id="terminos" name="terminos" required>
                    <label for="terminos">
                        Acepto los
                        <span class="link-terminos" id="abrirTerminos">Términos y condiciones</span>
                    </label>
                </div>

                <button type="submit" class="btn-crear">Crear cuenta</button>
            </form>

            <p class="texto-login">¿Ya tienes una cuenta? <a href="loginOrganizador.php">Inicia sesión aquí</a></p>
        </div>

        <!-- ===================== MODAL TÉRMINOS ===================== -->
        <div id="modal-terminos" class="modal">
            <div class="modal-content">
                <span class="cerrar-modal" id="cerrarModal">&times;</span>
                <h2>Términos y Condiciones</h2>
                <p>Como organizador de eventos en PUMFEST, te comprometes a cumplir con nuestras políticas de
                    transparencia y calidad. Los eventos creados estarán sujetos a verificación antes de su publicación.
                </p>
                <button id="aceptarTerminos" class="btn-crear">Aceptar</button>
            </div>
        </div>
    </main>

    <footer class="footer">
        <div class="footer-bottom">
            <p>© 2025 PUMFEST. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script src="../../JS/crearCuenta.js" defer></script>
    <script src="../../JS/global.js"></script>
</body>

</html>