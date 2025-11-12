<?php
session_start();
require_once "../conexion.php";
require_once "../../vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (!isset($_SESSION['verificar_correo'])) {
    echo json_encode(["status" => "error", "msg" => "sin_sesion"]);
    exit;
}

$usuario = $_SESSION['verificar_correo'];
$usuario_id = $usuario['id'];
$email = $usuario['correo'];
$nombre = $usuario['nombre'];

// Limpiar códigos viejos
$conn->query("DELETE FROM codigos_verificacion WHERE expira_en < NOW()");

// Crear código
$codigo = rand(10000, 99999);
$expira_en = date("Y-m-d H:i:s", strtotime("+2 minutes"));

$stmt = $conn->prepare("INSERT INTO codigos_verificacion (usuario_id, codigo, expira_en, usado) VALUES (?, ?, ?, 0)");
$stmt->bind_param("iss", $usuario_id, $codigo, $expira_en);
$stmt->execute();

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = "smtp.gmail.com";
    $mail->SMTPAuth = true;
    $mail->Username = "pumfest2025@gmail.com";
    $mail->Password = "szmt xdod ccza ukqh";
    $mail->SMTPSecure = "tls";
    $mail->Port = 587;

    $mail->setFrom("pumfest2025@gmail.com", "PUMFEST");
    $mail->addAddress($email, $nombre);

    $mail->isHTML(true);
    $mail->Subject = "Código de verificación - PUMFEST Organizador";
    $mail->Body = "<h2>Código: <b>$codigo</b></h2>";

    $mail->send();

    echo json_encode(["status" => "ok", "msg" => "Código enviado"]);
} catch (Exception $e) {
    echo json_encode(["status" => "error", "msg" => "No se pudo enviar"]);
}
