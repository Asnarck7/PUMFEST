<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../conexion.php";
header('Content-Type: text/plain; charset=UTF-8');

// ---------------------------------------------
// 🔒 Verificar sesión
// ---------------------------------------------
if (!isset($_SESSION['organizador'])) {
    echo "sin_sesion";
    exit();
}

$org = $_SESSION['organizador'];
$usuario_id = $org['usuario_id'];

// ---------------------------------------------
// 📩 Recibir datos del formulario
// ---------------------------------------------
$codigo = $_POST['codigo'] ?? '';
$nueva = $_POST['nueva_contrasena'] ?? '';

if (empty($codigo) || empty($nueva)) {
    echo "faltan_datos";
    exit();
}

// ---------------------------------------------
// 🔍 Validar código
// ---------------------------------------------
$sql = "SELECT * FROM codigos_verificacion 
        WHERE usuario_id=? AND tipo='cambio_password' 
        AND usado=0 ORDER BY id DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$res = $stmt->get_result();

if ($row = $res->fetch_assoc()) {
    if ($row['codigo'] != $codigo) {
        echo "invalido";
        exit();
    }

    if (strtotime($row['expira_en']) < time()) {
        echo "expirado";
        exit();
    }

    // -----------------------------------------
    // 🔑 Actualizar contraseña
    // -----------------------------------------
    $hash = password_hash($nueva, PASSWORD_DEFAULT);
    $sqlU = "UPDATE usuarios SET password=? WHERE usuario_id=?";
    $stmtU = $conn->prepare($sqlU);
    $stmtU->bind_param("si", $hash, $usuario_id);
    $stmtU->execute();

    // Marcar código como usado
    $conn->query("UPDATE codigos_verificacion SET usado=1 WHERE id=" . $row['id']);

    echo "actualizada";
} else {
    echo "sin_codigo";
}
?>