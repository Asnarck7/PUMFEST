<?php
session_start();

// ✅ Verificar sesión admin
if (!isset($_SESSION['admin'])) {
  header("Location: adminLogin.php");
  exit();
}

$admin = $_SESSION['admin'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Panel de Administración | PUMFEST</title>
  <link rel="stylesheet" href="../../CSS/CSS_Admin/panelAdmin.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="../../JS/JS_Admin/panelAdmin.js" defer></script>
</head>
<!--nota importante en la base de datos --nota
 - destacado (1/0) → controla el banner animado

 - es_destacado (1/0) → controla la sección de destacados en la página-->

<body>
  <div class="panel-container">
    <header>
      <h1>🎧 Bienvenido, <?= htmlspecialchars($admin['nombre']) ?> 👋</h1>
      <p>Permisos: <strong><?= htmlspecialchars($admin['permisos']) ?></strong></p>
    </header>

    <main>
      <div class="botones">
        <button class="btn btn-verificar">✅ Verificar Organizadores 👁️‍🗨️</button>
        <button class="btn btn-lista">📋 Lista de Verificados 📋</button>
        <button class="btn btn-banners">🌟 Administrar Eventos BANNER 🌟</button>
        <button class="btn btn-destacados"> 🐈‍⬛ Administrar Eventos DESTACADOS</button>
        <button class="btn btn-asistentes">🧍 Ver Asistentes</button>
        <button class="btn btn-solicitudes">📩 Solicitudes de Organizadores 🚨</button>
      </div>
    </main>

    <footer>
      <button class="logout-btn">Cerrar Sesión</button>
    </footer>
  </div>
</body>

</html>