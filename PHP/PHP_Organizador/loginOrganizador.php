<?php
session_start();
require_once "../conexion.php";

// 🧱 Inicializar variables de sesión
if (!isset($_SESSION['intentos_organizador'])) {
    $_SESSION['intentos_organizador'] = 0;
    $_SESSION['bloqueado_hasta'] = null;
}

$mensaje_bloqueo = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['email']);
    $password = $_POST['password'];
    $max_intentos = 3; // 🔹 Máximo de intentos permitidos
    $tiempo_bloqueo = 5 * 60; // 🔹 Bloqueo 5 minutos (en segundos)

    // 🚫 Verificar bloqueo activo
    if (isset($_SESSION['bloqueado_hasta']) && time() < $_SESSION['bloqueado_hasta']) {
        $restante = ceil(($_SESSION['bloqueado_hasta'] - time()) / 60);
        echo json_encode([
            'ok' => false,
            'mensaje' => "🚫 Has excedido el número de intentos. Espera {$restante} minuto(s) para volver a intentar."
        ]);
        exit;
    }

    // ✅ Intento normal de login
    $sql = "SELECT usuario_id, nombre, password 
            FROM usuarios 
            WHERE email = ? AND rol = 'organizador' 
            LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    $mensaje_extra = ""; // para mostrar intentos restantes

    if ($result->num_rows === 0) {
        $_SESSION['intentos_organizador']++;
        $mensaje_bloqueo = "❌ Correo no registrado.";
    } else {
        $usuario = $result->fetch_assoc();
        $usuario_id = (int) $usuario['usuario_id'];

        if (!password_verify($password, $usuario['password'])) {
            $_SESSION['intentos_organizador']++;
            $mensaje_bloqueo = "❌ Contraseña incorrecta.";
        } else {
            // 🟢 Éxito → reiniciar contador
            $_SESSION['intentos_organizador'] = 0;
            $_SESSION['bloqueado_hasta'] = null;

            $sqlOrg = "SELECT organizador_id, verificado, biografia 
                       FROM organizadores 
                       WHERE usuario_id = ? LIMIT 1";
            $stmt2 = $conn->prepare($sqlOrg);
            $stmt2->bind_param("i", $usuario_id);
            $stmt2->execute();
            $res2 = $stmt2->get_result();

            if ($res2->num_rows === 0) {
                $mensaje_bloqueo = "❌ No existe registro en la tabla organizadores.";
            } else {
                $org = $res2->fetch_assoc();

                if ((int) $org['verificado'] !== 1) {
                    $mensaje_bloqueo = "⚠ Tu cuenta aún no ha sido verificada por el administrador.";
                } else {
                    $_SESSION['organizador'] = [
                        'usuario_id' => $usuario_id,
                        'organizador_id' => (int) $org['organizador_id'],
                        'nombre' => $usuario['nombre'],
                        'email' => $correo,
                        'biografia' => $org['biografia'],
                        'verificado' => (int) $org['verificado']
                    ];

                    echo json_encode(['ok' => true, 'redirect' => 'panelOrganizador.php']);
                    exit;
                }
            }
        }
    }

    // 🚨 Si alcanzó el máximo → bloquear 5 minutos
    if ($_SESSION['intentos_organizador'] >= $max_intentos) {
        $_SESSION['bloqueado_hasta'] = time() + $tiempo_bloqueo;
        $_SESSION['intentos_organizador'] = 0;
        $mensaje_bloqueo = "🚫 Has alcanzado el límite de intentos. Tu acceso se bloqueará por 5 minutos.";
    } else {
        // 🧮 Mostrar cuántos intentos quedan antes del bloqueo
        $restantes = $max_intentos - $_SESSION['intentos_organizador'];
        if ($restantes > 0) {
            $mensaje_extra = " ⚠️ Te quedan {$restantes} intento(s) antes del bloqueo.";
        }
    }

    // 🔙 Respuesta JSON
    echo json_encode([
        'ok' => false,
        'mensaje' => $mensaje_bloqueo . $mensaje_extra
    ]);
    exit;
}
?>




<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión | Organizador - PUMFEST</title>

    <!-- ✅ Icono -->
    <link rel="icon" type="image/png" href="../../LogoPUMFEST/LogoPUMFESTsinFondo.png">

    <!-- ✅ Estilos -->
    <link rel="stylesheet" href="../../CSS/CSS_Organizador/login-organizador.css">

    <link rel="stylesheet" href="../../CSS/global.css">
</head>

<body>

    <div id="loader" class="loader" style="display:none;">Cargando...</div>
    <!-- ============================= HEADER ============================= -->
    <header class="header">
        <div class="header-top">
            <div class="logo" onclick="window.location.href='OrganizadorIndex.php'"></div>
        </div>
    </header>
    <main class="container">
        <h1>Iniciar Sesión - Organizador</h1>

        <div class="login-image-Organizador">
            <img src="../../LogoPUMFEST/LoginOrganizador.png" alt="Logo PUMFEST">
        </div>

        <form method="POST" class="login-form" action="">
            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input type="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit" class="btn-login">Entrar</button>
        </form>

        <p>¿No tienes cuenta? <a href="crearOrganizador.php">Regístrate aquí</a></p>
    </main>

    <footer class="footer">
        <p>© 2025 PUMFEST</p>
    </footer>

    <script src="../../JS/global.js"></script>
    <script src="../../JS/JS_Organizador/loginOrganizador.js"></script>
</body>

</html>