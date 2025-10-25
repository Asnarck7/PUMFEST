<?php
session_start();
require_once "conexion.php";

// Evitar acceso directo por GET
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: crear-asistente.php");
    exit;
}

// ====== 1️⃣ Recibir y sanitizar datos ======
$nombre = trim($_POST['nombre'] ?? '');
$apellido = trim($_POST['apellido'] ?? '');
$sexo = trim($_POST['sexo'] ?? '');
$telefono = trim($_POST['telefono'] ?? '');
$fecha_nacimiento = trim($_POST['fecha_nacimiento'] ?? '');
$correo = trim($_POST['correo'] ?? '');
$confirmar_correo = trim($_POST['confirmar_correo'] ?? '');
$contrasena = $_POST['contrasena'] ?? '';
$confirmar = $_POST['confirmar'] ?? '';

// ====== 2️⃣ Validaciones básicas ======
if ($correo !== $confirmar_correo) {
    echo "<script>alert('Los correos no coinciden'); window.history.back();</script>";
    exit;
}
if ($contrasena !== $confirmar) {
    echo "<script>alert('Las contraseñas no coinciden'); window.history.back();</script>";
    exit;
}
if (empty($nombre) || empty($correo) || empty($contrasena)) {
    echo "<script>alert('Completa todos los campos requeridos'); window.history.back();</script>";
    exit;
}

// ====== 3️⃣ Verificar si el correo ya existe ======
$stmt = $conn->prepare("SELECT id FROM asistentes WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->close();
    echo "<script>alert('Ya existe una cuenta con ese correo'); window.history.back();</script>";
    exit;
}
$stmt->close();

// ====== 4️⃣ Insertar nuevo asistente ======
$hash = password_hash($contrasena, PASSWORD_DEFAULT);
$insert = $conn->prepare("
    INSERT INTO asistentes (nombre, apellido, sexo, telefono, fecha_nacimiento, correo, contrasena) 
    VALUES (?, ?, ?, ?, ?, ?, ?)
");
$insert->bind_param("sssssss", $nombre, $apellido, $sexo, $telefono, $fecha_nacimiento, $correo, $hash);

if ($insert->execute()) {
    // ====== 5️⃣ Crear sesión automáticamente ======
    $_SESSION['asistente'] = [
        'id' => $insert->insert_id,
        'nombre' => $nombre,
        'correo' => $correo
    ];

    $insert->close();

    // ====== 6️⃣ Redirigir sin usar alertas ======
    header("Location: index.php");
    exit;

} else {
    $error = addslashes($conn->error);
    $insert->close();
    echo "<script>alert('Error al registrar: {$error}'); window.history.back();</script>";
    exit;
}
?>
