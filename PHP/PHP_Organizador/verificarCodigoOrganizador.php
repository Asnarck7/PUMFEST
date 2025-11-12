<?php
session_start();
require_once "../conexion.php";

if (!isset($_SESSION['verificar_correo'])) {
    exit("sin_sesion");
}

$usuario = $_SESSION['verificar_correo'];
$usuario_id = $usuario['id'];
$codigo = $_POST['codigo'] ?? '';

$sql = "SELECT * FROM codigos_verificacion 
        WHERE usuario_id = ? AND codigo = ? AND usado = 0
        ORDER BY id DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $usuario_id, $codigo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) exit("invalido");

$reg = $result->fetch_assoc();

if (strtotime($reg['expira_en']) < time()) exit("expirado");

$conn->query("UPDATE codigos_verificacion SET usado = 1 WHERE id = {$reg['id']}");
$conn->query("UPDATE usuarios SET email_verificado = 1 WHERE usuario_id = $usuario_id");

unset($_SESSION['verificar_correo']);
echo "verificado";
