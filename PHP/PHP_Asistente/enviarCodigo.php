<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once "../conexion.php";
require_once "../../vendor/autoload.php"; // Carga PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// ==============================
// 🔐 Verificar sesión activa de verificación
// ==============================
if (!isset($_SESSION['verificar_correo'])) {
    http_response_code(403);
    echo json_encode(["status" => "error", "msg" => "sin_sesion"]);
    exit();
}

// ==============================
// 📦 Datos del usuario en verificación
// ==============================
$usuario = $_SESSION['verificar_correo'];
$usuario_id = $usuario['id'];
$email = $usuario['correo'];
$nombre = $usuario['nombre'];

// ==============================
// 🧹 Limpiar códigos expirados
// ==============================
$conn->query("DELETE FROM codigos_verificacion WHERE expira_en < NOW()");

// ==============================
// 🕐 Verificar si se envió un código en los últimos 5 minutos
// ==============================
$sqlCheck = "SELECT creado_en FROM codigos_verificacion 
             WHERE usuario_id = ? 
             ORDER BY id DESC 
             LIMIT 1";
$stmtCheck = $conn->prepare($sqlCheck);
$stmtCheck->bind_param("i", $usuario_id);
$stmtCheck->execute();
$result = $stmtCheck->get_result();

if ($row = $result->fetch_assoc()) {
    $ultimo_envio = strtotime($row['creado_en']);
    $ahora = time();
    $diferencia = ($ahora - $ultimo_envio) / 60; // minutos

    if ($diferencia < 5) {
        echo json_encode([
            "status" => "espera",
            "msg" => "Ya se envió un código recientemente. Espera 5 minutos antes de solicitar otro."
        ]);
        exit();
    }
}

// ==============================
// 🎲 Generar nuevo código (válido por 2 minutos)
// ==============================
$codigo = rand(10000, 99999);
$expira_en = date("Y-m-d H:i:s", strtotime("+2 minutes"));

// ==============================
// 💾 Guardar nuevo código
// ==============================
$sql = "INSERT INTO codigos_verificacion (usuario_id, codigo, expira_en, usado, creado_en)
        VALUES (?, ?, ?, 0, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $usuario_id, $codigo, $expira_en);
$stmt->execute();

// ==============================
// ✉️ Enviar correo con PHPMailer
// ==============================
$mail = new PHPMailer(true);

try {
    // Configuración del servidor SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'pumfest2025@gmail.com';
    $mail->Password = 'szmt xdod ccza ukqh'; // Contraseña de aplicación
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Información del remitente y destinatario
    $mail->setFrom('pumfest2025@gmail.com', 'PUMFEST');
    $mail->addAddress($email, $nombre);

    // Contenido del correo
    $mail->isHTML(true);
    $mail->Subject = 'Código de verificación - PUMFEST';
    $mail->Body = "
        <div style='font-family:Arial,sans-serif; padding:10px;'>
            <h2 style='color:#ffb84d;'>🔒 Código de Verificación</h2>
            <p>Hola <b>$nombre</b>,</p>
            <p>Tu código para verificar tu cuenta es:</p>
            <p style='font-size:22px; font-weight:bold; color:#333;'>$codigo</p>
            <p>Este código expirará en <b>2 minutos</b>.</p>
            <br>
            <hr>
            <p style='font-size:12px; color:gray;'>© 2025 PUMFEST. No respondas a este correo.</p>
        </div>
    ";

    $mail->send();

    echo json_encode(["status" => "ok", "msg" => "enviado"]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "msg" => "Error al enviar correo: " . $mail->ErrorInfo]);
}
?>