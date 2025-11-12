<?php
session_start();
require_once "../conexion.php";

// ✅ Validar sesión de organizador
if (!isset($_SESSION['organizador'])) {
    header("Location: loginOrganizador.php");
    exit();
}

$org = $_SESSION['organizador'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel del Organizador</title>

    <!-- estilos globales -->
    <link rel="stylesheet" href="../../CSS/global.css">

    <!-- estilos propios -->
    <link rel="stylesheet" href="../../CSS/CSS_Organizador/panelOrganizador.css">

    <!-- Librería SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts -->
    <script defer src="../../JS/JS_Organizador/panelOrganizador.js"></script>
    <script defer src="../../JS/JS_Organizador/solicitudEliminarCuenta.js"></script>
</head>

<body>

<div class="panel-container">
    <h1>🎉 Bienvenido, <?php echo htmlspecialchars($org['nombre']); ?>!</h1>
    <h3>Panel de gestión de tus eventos y perfil</h3>

    <a href="eventosOrganizador.php" class="btn btn-eventos">📅 Lista de eventos</a>
    <a href="editarOrganizador.php" class="btn btn-editar">⚙️ Editar perfil</a>

    <!-- Nuevo botón -->
    <button class="btn btn-eliminar" id="solicitarEliminarCuenta">🧾 Solicitud de la Cuenta</button>

    <button class="btn btn-logout" id="logoutBtn">🚪 Cerrar sesión</button>
</div>

<script src="../../JS/global.js"></script>
</body>
</html>