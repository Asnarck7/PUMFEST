<?php
session_start();
require_once "../conexion.php";

// Si la petición es AJAX (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');

    $clave_ingresada = $_POST['clave'] ?? '';
    $clave_correcta = 'a*!1';

    if (!isset($_SESSION['intentos_admin']))
        $_SESSION['intentos_admin'] = 0;
    if (!isset($_SESSION['bloqueado_hasta']))
        $_SESSION['bloqueado_hasta'] = 0;

    $ahora = time();
    if ($ahora < $_SESSION['bloqueado_hasta']) {
        echo json_encode(["status" => "bloqueado", "mensaje" => "⏳ Espera 5 minutos antes de intentar de nuevo."]);
        exit();
    }

    if ($clave_ingresada === $clave_correcta) {
        $sql = "SELECT u.usuario_id, u.nombre, u.email, a.permisos
                FROM usuarios u
                JOIN administradores a ON a.usuario_id = u.usuario_id
                WHERE u.rol = 'administrador' LIMIT 1";
        $resultado = $conn->query($sql);

        if ($resultado && $resultado->num_rows > 0) {
            $admin = $resultado->fetch_assoc();
            $_SESSION['clave_admin_ok'] = true; // ← acceso especial validado
            $_SESSION['admin_temp'] = $admin;   // datos provisionales
            echo json_encode(["status" => "ok", "mensaje" => "Acceso concedido"]);
        } else {
            echo json_encode(["status" => "error", "mensaje" => "No se encontró al administrador."]);
        }
    } else {
        $_SESSION['intentos_admin']++;
        if ($_SESSION['intentos_admin'] >= 3) {
            $_SESSION['bloqueado_hasta'] = time() + (5 * 60);
            $_SESSION['intentos_admin'] = 0;
            echo json_encode(["status" => "bloqueado", "mensaje" => "🚫 3 intentos fallidos. Bloqueado 5 min."]);
        } else {
            echo json_encode(["status" => "error", "mensaje" => "❌ Clave incorrecta. Intento {$_SESSION['intentos_admin']} de 3."]);
        }
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <!-- ✅ Icono -->
    <link rel="icon" type="image/png" href="../../LogoPUMFEST/LogoPUMFESTsinFondo.png">
    <title>Acceso Especial Admin | PUMFEST</title>
    <link rel="stylesheet" href="../../CSS/CSS_Admin/adminLogin.css">
</head>

<body>
    <div class="login-container">
        <h2>🔐 Clave de Acceso<br>Especial</h2>
        <input type="password" id="claveAdmin" placeholder="Ingresa la clave especial">
        <button id="btnEntrar">Validar</button>
        <p id="mensaje"></p>
        <div id="loader" class="loader" style="display:none;"></div>
    </div>

    <script src="../../JS/JS_Admin/loginAdmin.js"></script>
</body>

</html>