<?php
session_start();
require_once "../conexion.php";

// 🔐 Validar que se ingresó desde adminLogin
if (!isset($_SESSION['clave_admin_ok'])) {
  header("Location: adminLogin.php");
  exit();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $password = trim($_POST['password'] ?? '');

  // ✅ Buscar administrador
  $sql = "SELECT u.usuario_id, u.nombre, u.email, u.password, a.permisos
          FROM usuarios u
          JOIN administradores a ON a.usuario_id = u.usuario_id
          WHERE u.email = ? AND u.rol = 'administrador' LIMIT 1";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $resultado = $stmt->get_result();

  if ($resultado->num_rows > 0) {
    $admin = $resultado->fetch_assoc();
    $hash = $admin['password'];

    // ✅ Detectar tipo de hash (bcrypt o SHA256)
    $isBcrypt = str_starts_with($hash, '$2y$');

    $valida = $isBcrypt
      ? password_verify($password, $hash)
      : hash_equals($hash, hash('sha256', $password));

    if ($valida) {
      // ✅ Guardar sesión del admin
      $_SESSION['admin'] = [
        "usuario_id" => $admin['usuario_id'],
        "nombre" => $admin['nombre'],
        "email" => $admin['email'],
        "permisos" => $admin['permisos'],
        "rol" => "administrador"
      ];

      header("Location: panelAdmin.php");
      exit();
    } else {
      $error = "❌ Contraseña incorrecta.";
    }
  } else {
    $error = "❌ No se encontró el administrador.";
  }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar Sesión | Admin</title>
  <link rel="stylesheet" href="../../CSS/CSS_Admin/iniciarSesionAdmin.css">
</head>
<body>
  <div class="login-container">
    <h2>Inicio de Sesión Admin</h2>
    <form method="POST">
      <input type="email" name="email" placeholder="Correo electrónico" required>
      <input type="password" name="password" placeholder="Contraseña" required>
      <button type="submit">Ingresar</button>
      <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    </form>
  </div>
</body>
</html>