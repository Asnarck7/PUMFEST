<?php
// ==========================
// 🧠 Iniciar sesión y conexión ASISTENTE
// ==========================

// Inicia o reanuda la sesión actual (necesario para acceder a $_SESSION)
session_start();

// Incluye el archivo de conexión a la base de datos
require_once "../conexion.php";


// ==========================
// 🔒 Verificar sesión activa
// ==========================

// Si el usuario NO ha iniciado sesión, lo redirige al login
if (!isset($_SESSION['asistente'])) {
  header("Location: ../PHP_Asistente/iniciar-asistente.php"); // Redirige al formulario de inicio de sesión
  exit(); // Detiene la ejecución del script
}


// ==========================
// 👤 Obtener datos del usuario logueado
// ==========================

// Guarda los datos del asistente almacenados en la sesión
$asistente = $_SESSION['asistente'];

// Extrae el ID del usuario (clave primaria en la base de datos)
$usuario_id = $asistente['id'];


// ==========================
// 📝 Recibir datos enviados por el formulario
// ==========================

// Toma los valores enviados mediante POST desde el formulario HTML
$nombre = $_POST['nombre'];
$apellido = $_POST['apellido'];
$email = $_POST['email'];
$telefono = $_POST['telefono'];


// ==========================
// 💾 Actualizar datos en la base de datos
// ==========================

// Prepara la consulta SQL para actualizar los datos del usuario
$sql = "UPDATE usuarios SET nombre = ?, apellido = ?, email = ?, telefono = ? WHERE usuario_id = ?";

// Prepara la sentencia evitando inyección SQL
$stmt = $conn->prepare($sql);

// Asocia los valores a los parámetros de la consulta (s = string, i = integer)
$stmt->bind_param("ssssi", $nombre, $apellido, $email, $telefono, $usuario_id);


// ==========================
// ✅ Ejecutar y redirigir
// ==========================

// Si la actualización fue exitosa
if ($stmt->execute()) {
  // Redirige al perfil con un parámetro indicando éxito
  header("Location: ../PHP_Asistente/perfilAsistente.php?actualizado=1");
} else {
  // Redirige al perfil con un parámetro indicando error
  header("Location: ../PHP_Asistente/perfilAsistente.php?error=1");
}

// Finaliza el script para evitar cualquier ejecución adicional
exit();
?>
