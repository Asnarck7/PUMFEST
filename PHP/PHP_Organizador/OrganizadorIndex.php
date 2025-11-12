<?php
// ✅ Iniciar sesión solo si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Si el organizador está logueado, obtener su información
if (isset($_SESSION['organizador'])) {
    $logueado = true;
    $nombreUsuario = htmlspecialchars($_SESSION['organizador']['nombre']);
} else {
    $logueado = false;
    $nombreUsuario = "";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Organizador | PUMFEST</title>
    <link rel="icon" type="image/png" href="../../LogoPUMFEST/LogoPUMFESTsinFondo.png">
    <link rel="stylesheet" href="../../CSS/iniciar-sesion.css">
    <link rel="stylesheet" href="../../CSS/global.css">
    <link rel="stylesheet" href="../../CSS/Index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <!-- ============================= HEADER ============================= -->
    <header class="header">
        <div class="header-top">
            <div class="logo" onclick="window.location.href='../../index.php'"></div>
        </div>

        <!-- Usuario -->
        <div class="user-menu">
            <button id="userBtn" class="user-icon">
                <img src="../../LogoPUMFEST/LoginPUMFESTsinfondo.png" alt="Usuario" class="user-img" />

                <span class="login-text">
                    <div class="loader-container">
                        <div class="flipping-cards">
                            <?php
                            // Si está logueado, mostrar su nombre animado
                            // Si no, mostrar "login"
                            $texto = $logueado ? $nombreUsuario : "login";

                            // Mostrar solo el primer nombre
                            $texto = explode(" ", trim($texto))[0];

                            // Mostrar cada letra como tarjeta animada
                            foreach (str_split($texto) as $letra) {
                                echo '<div class="card">' . htmlspecialchars($letra) . '</div>';
                            }
                            ?>
                        </div>
                    </div>
                </span>
            </button>

            <!-- Menú dinámico -->
            <div id="userDropdown" class="user-dropdown">
                <?php if ($logueado): ?>
                    <span class="user-name">👋 <?php echo htmlspecialchars($nombreUsuario); ?></span>
                    <a href="panelOrganizador.php"><i class="fa-solid fa-user"></i> Panel de Eventos</a>
                    <a href="../cerrar-sesion.php" class="logout-btn">Cerrar sesión</a>
                <?php else: ?>
                    <a href="loginOrganizador.php"><i class="fa-solid fa-right-to-bracket"></i> Iniciar sesión</a>
                    <a href="crearOrganizador.php"><i class="fa-solid fa-user-plus"></i> Crear cuenta</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <main class="container">
        <h1>🎤 Bienvenido al Módulo de Organizadores</h1>
        <!-- Agregar el texto de lo que hace el rol aqui, no se que hace esto kevin jajaja -->
    </main>

    <footer class="footer">
        <p>© 2025 PUMFEST. Todos los derechos reservados.</p>
    </footer>

    <script src="../../JS/global.js"></script>
    <script src="../../JS/PROMvideo.js"></script>
    <script src="../../JS/JS_Organizador/OrganizadorIndex.js"></script>

</body>

</html>
