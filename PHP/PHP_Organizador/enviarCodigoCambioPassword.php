<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=UTF-8');

require_once "../conexion.php";
require_once "../../vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ========================================
// 🔒 Verificar sesión activa del organizador
// ========================================
if (!isset($_SESSION['organizador'])) {
    http_response_code(403);
    echo json_encode(["status" => "error", "msg" => "sin_sesion"]);
    exit();
}

// ========================================
// 📦 Datos del organizador logueado
// ========================================
$org = $_SESSION['organizador'];
$usuario_id = $org['usuario_id'];  // ✅ campo correcto
$email = $org['email'] ?? null;
$nombre = $org['nombre'] ?? 'Organizador';

if (!$email) {
    echo json_encode(["status" => "error", "msg" => "No hay correo asociado."]);
    exit();
}

// ========================================
// 🧹 Eliminar códigos expirados
// ========================================
$conn->query("DELETE FROM codigos_verificacion WHERE expira_en < NOW()");

// ========================================
// ⏱️ Verificar si ya se envió un código hace poco (5 min)
// ========================================
$sqlCheck = "SELECT creado_en FROM codigos_verificacion 
             WHERE usuario_id = ? AND tipo = 'cambio_password'
             ORDER BY id DESC LIMIT 1";
$stmtCheck = $conn->prepare($sqlCheck);
$stmtCheck->bind_param("i", $usuario_id);
$stmtCheck->execute();
$result = $stmtCheck->get_result();

if ($row = $result->fetch_assoc()) {
    $ultimo_envio = strtotime($row['creado_en']);
    //bloqueo de 5 minitos para enviar nuevo codigo se puede poner hasta para un dia...
    //ejem 
    //if (time() - $ultimo_envio < 86400) { // 24 horas = 1 día
    if (time() - $ultimo_envio < 300) { // 5 min
        echo json_encode([
            "status" => "espera",
            "msg" => "Ya solicitaste un código recientemente. Espera 5 minutos."
        ]);
        exit();
    }
}

// ========================================
// 🎲 Generar nuevo código (válido 2 min)
// ========================================
$codigo = rand(100000, 999999);
//para un dia 
//$expira_en = date("Y-m-d H:i:s", strtotime("+1 day"));
$expira_en = date("Y-m-d H:i:s", strtotime("+2 minutes"));

// ========================================
// 💾 Guardar en codigos_verificacion
// ========================================
$sql = "INSERT INTO codigos_verificacion (usuario_id, codigo, tipo, expira_en, usado, creado_en)
        VALUES (?, ?, 'cambio_password', ?, 0, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $usuario_id, $codigo, $expira_en);
$stmt->execute();

// ========================================
// ✉️ Enviar correo con PHPMailer
// ========================================
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'pumfest2025@gmail.com';
    $mail->Password   = 'szmt xdod ccza ukqh'; // contraseña de aplicación
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('pumfest2025@gmail.com', 'PUMFEST');
    $mail->addAddress($email, $nombre);

    $mail->isHTML(true);
    $mail->Subject = 'Cambio de contraseña - PUMFEST';
    $mail->Body = "
        <div style='font-family:Arial,sans-serif;padding:10px'>
            <h2 style='color:#ffb84d'>🔑 Cambio de contraseña</h2>
            <p>Hola <b>$nombre</b>,</p>
            <p>Tu código para cambiar la contraseña es:</p>
            <p style='font-size:22px;font-weight:bold;color:#333'>$codigo</p>
            <p>Este código expirará en <b>2 minutos</b>.</p>
            <hr>
            <small style='color:gray'>© 2025 PUMFEST. No respondas a este correo.</small>
        </div>";

    $mail->send();
    echo json_encode(["status" => "ok", "msg" => "Código enviado con éxito."]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "msg" => "Error al enviar el correo: " . $mail->ErrorInfo
    ]);
}
?>
