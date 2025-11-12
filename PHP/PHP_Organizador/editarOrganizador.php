<?php
session_start();
require_once "../conexion.php";

if (!isset($_SESSION['organizador'])) {
    header("Location: loginOrganizador.php");
    exit();
}

$org = $_SESSION['organizador'];
$usuario_id = $org['usuario_id'];

// ✅ Traer datos del usuario
$sql = "SELECT nombre, apellido, telefono, email FROM usuarios WHERE usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$userData = $stmt->get_result()->fetch_assoc();

// ✅ Traer biografía desde organizadores
$sql2 = "SELECT biografia FROM organizadores WHERE usuario_id = ?";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $usuario_id);
$stmt2->execute();
$orgData = $stmt2->get_result()->fetch_assoc();

// ✅ Guardar cambios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $telefono = trim($_POST['telefono']);
    $biografia = trim($_POST['biografia']);

    // ✅ Cambiar contraseña solo si se ingresó
    $nuevaPassword = $_POST['password'] ?? '';
    $updatePass = false;

    if (!empty($nuevaPassword)) {
        $hash = password_hash($nuevaPassword, PASSWORD_DEFAULT);
        $updatePass = true;
    }

    // ✅ Actualizar tabla usuarios
    if ($updatePass) {
        $sqlU = "UPDATE usuarios SET nombre=?, apellido=?, telefono=?, password=? WHERE usuario_id=?";
        $stmtU = $conn->prepare($sqlU);
        $stmtU->bind_param("ssssi", $nombre, $apellido, $telefono, $hash, $usuario_id);
    } else {
        $sqlU = "UPDATE usuarios SET nombre=?, apellido=?, telefono=? WHERE usuario_id=?";
        $stmtU = $conn->prepare($sqlU);
        $stmtU->bind_param("sssi", $nombre, $apellido, $telefono, $usuario_id);
    }

    $stmtU->execute();

    // ✅ Actualizar tabla organizadores
    $sqlO = "UPDATE organizadores SET biografia=? WHERE usuario_id=?";
    $stmtO = $conn->prepare($sqlO);
    $stmtO->bind_param("si", $biografia, $usuario_id);
    $stmtO->execute();

    // ✅ Actualizar sesión
    $_SESSION['organizador']['nombre'] = $nombre;
    $_SESSION['organizador']['biografia'] = $biografia;

    header("Location: panelOrganizador.php?update=ok");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil</title>
    <link rel="stylesheet" href="../../CSS/CSS_Organizador/editarOrganizador.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

<div class="container">
    <h1>⚙️ Editar Perfil</h1>

    <form method="POST">
        <label>Nombre</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($userData['nombre']) ?>" required>

        <label>Apellido</label>
        <input type="text" name="apellido" value="<?= htmlspecialchars($userData['apellido']) ?>" required>

        <label>Teléfono</label>
        <input type="text" name="telefono" value="<?= htmlspecialchars($userData['telefono']) ?>">

        <label>Biografía</label>
        <textarea name="biografia" rows="5"><?= htmlspecialchars($orgData['biografia']) ?></textarea>

        <!-- 🔒 Botón para abrir el overlay -->
        <div class="cambiar-pass">
            <button type="button" id="btnCambiarPass" class="btn-codigo">🔐 Cambiar contraseña</button>
        </div>

        <button class="btn-guardar" type="submit">✅ Guardar Cambios</button>
        <button class="btn-volver" type="button" onclick="location.href='panelOrganizador.php'">⬅ Volver</button>
    </form>
</div>

<!-- 🔹 Overlay para cambio de contraseña -->
<div id="overlayCambioPass" class="overlay">
    <div class="overlay-content">
        <h2>🔒 Cambiar contraseña</h2>
        <p>Se enviará un código de verificación a tu correo:</p>
        <p><strong><?= htmlspecialchars($userData['email']) ?></strong></p>
        <button type="button" id="enviarCodigo">📨 Enviar código</button>

        <div id="verificarCodigo" class="hidden">
            <input type="text" id="codigoInput" placeholder="Código de verificación">
            <input type="password" id="nuevaPassword" placeholder="Nueva contraseña">
            <input type="password" id="confirmarPassword" placeholder="Confirmar nueva contraseña">
            <button type="button" id="confirmarCambio">✅ Confirmar cambio</button>
        </div>

        <button type="button" id="cerrarOverlay">❌ Cancelar</button>
    </div>
</div>


<!-- Scripts -->
<script src="../../JS/JS_Organizador/editarOrganizador.js"></script>
<script src="../../JS/JS_Organizador/cambiarPassword.js"></script>

</body>
</html>
