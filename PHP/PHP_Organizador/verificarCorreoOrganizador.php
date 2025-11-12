<?php
session_start();
require_once "../conexion.php";

// ✅ Verificar sesión temporal
if (!isset($_SESSION['verificar_correo'])) {
  http_response_code(403);
  exit("sin_sesion");
}

$usuario = $_SESSION['verificar_correo'];
$email = htmlspecialchars($usuario['correo']);
$nombre = htmlspecialchars($usuario['nombre']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Verificar correo - Organizador</title>
  <link rel="stylesheet" href="../../CSS/global.css">
  <link rel="stylesheet" href="../../CSS/CSS_Organizador/verificarCorreoOrganizador.css">

</head>
<body>

  <div class="card">
    <h2>Verifica tu correo</h2>
    <p>Te enviamos un código al correo <b><?php echo $email; ?></b></p>

    <form id="formVerificar" onsubmit="return false;">
      <input type="text" id="codigo" name="codigo" placeholder="Código de verificación" maxlength="5" required>
      <button type="button" onclick="verificarCodigo()">Verificar</button>
    </form>

    <p id="msg"></p>

    <p><a href="#" onclick="reenviarCodigo()">🔁 Reenviar código</a></p>
  </div>

<script>
// ✅ ✅ ENVIAR EL CÓDIGO AUTOMÁTICAMENTE APENAS CARGA LA PÁGINA
document.addEventListener("DOMContentLoaded", () => {
    fetch("enviarCodigoOrganizador.php", { method: "POST" })
    .then(r => r.json())
    .then(d => {
        console.log("ENVÍO AUTOMÁTICO:", d);
        if (d.status === "ok") {
            console.log("✅ Código enviado automáticamente");
        } else {
            console.warn("⚠️ No se pudo enviar automáticamente:", d.msg);
        }
    });
});

// ✅ Verificar código
function verificarCodigo() {
  const formData = new FormData();
  formData.append("codigo", document.getElementById("codigo").value);

  fetch("verificarCodigoOrganizador.php", {
      method: "POST",
      body: formData
  })
  .then(r => r.text())
  .then(data => {
      const msg = document.getElementById("msg");

      if (data.trim() === "verificado") {
        msg.textContent = "✅ ¡Correo verificado! Espera aprobación del administrador.";
        msg.style.color = "green";
        setTimeout(() => window.location.href = "loginOrganizador.php", 2500);
      } 
      else if (data.trim() === "invalido") {
        msg.textContent = "❌ Código incorrecto.";
        msg.style.color = "red";
      }
      else if (data.trim() === "expirado") {
        msg.textContent = "⏰ Código expirado.";
        msg.style.color = "orange";
      }
      else {
        msg.textContent = "⚠️ Error: " + data;
        msg.style.color = "red";
      }
  });
}

// ✅ Reenviar código
function reenviarCodigo() {
  fetch("enviarCodigoOrganizador.php", {method:"POST"})
  .then(r => r.json())
  .then(d => alert(d.msg || "Código reenviado"));
}
</script>

<script src="../../JS/global.js"></script>
<script src="../../JS/JS_Organizador/verificarCorreoOrganizador.js"></script>

</body>
</html>