<?php
session_start();
require_once "../conexion.php";

// 🔐 Verificar sesión temporal activa
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
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verificar correo - PUMFEST</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../../CSS/CSS_Asistente/verificar-correo.css">

<!-- 🔹 Estilos del modal integrados -->
<style>
  .overlay {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0, 0, 0, 0.6);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
  }

  .modal {
    background: #1c1c1c;
    color: white;
    padding: 25px 30px;
    border-radius: 10px;
    max-width: 400px;
    text-align: center;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.4);
    animation: fadeIn 0.3s ease-in-out;
  }

  .modal p {
    margin: 10px 0;
  }

  .modal button {
    background: #0078ff;
    border: none;
    color: white;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    margin-top: 10px;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
  }
</style>

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

  <!-- 🔹 Script del modal + verificación -->
  <script>
    // 🪟 Función para mostrar mensajes en un modal
    function mostrarModal(mensaje, tipo = "info") {
      // Eliminar anterior
      const anterior = document.querySelector('.overlay');
      if (anterior) anterior.remove();

      // Definir color según tipo
      let color = "#0078ff", titulo = "ℹ️ Información";
      switch (tipo) {
        case "exito": color = "#28a745"; titulo = "✅ Éxito"; break;
        case "error": color = "#dc3545"; titulo = "❌ Error"; break;
        case "advertencia": color = "#ffc107"; titulo = "⚠️ Atención"; break;
      }

      // Crear modal
      const overlay = document.createElement('div');
      overlay.className = 'overlay';
      overlay.style.display = 'flex';
      overlay.innerHTML = `
        <div class="modal" style="border-top: 4px solid ${color}">
          <p style="color:${color}; font-weight:600; margin-bottom:8px;">${titulo}</p>
          <p>${mensaje}</p>
          <button id="btnAceptar" style="background:${color}">Aceptar</button>
        </div>
      `;
      document.body.appendChild(overlay);

      // Cerrar modal
      document.getElementById('btnAceptar').onclick = () => overlay.remove();
      setTimeout(() => { if (document.body.contains(overlay)) overlay.remove(); }, 5000);
    }

    // 🧩 Función principal
    function verificarCodigo() {
      const formData = new FormData();
      formData.append('codigo', document.getElementById('codigo').value);

      fetch("verificarCodigo.php", { method: "POST", body: formData })
        .then(res => res.text())
        .then(data => {
          console.log("Respuesta del servidor:", data);
          const resp = data.trim();

          if (resp === "actualizada" || resp === "verificado") {
            mostrarModal("¡Cuenta verificada correctamente! Redirigiendo...", "exito");
            setTimeout(() => window.location.href = "iniciar-asistente.php", 2000);
          } 
          else if (resp === "invalido") {
            mostrarModal("Código incorrecto. Intenta nuevamente.", "error");
          } 
          else if (resp === "expirado") {
            mostrarModal("El código ha expirado. Solicita uno nuevo.", "advertencia");
          } 
          else if (resp === "sin_sesion") {
            mostrarModal("Sesión no válida. Registra tu correo de nuevo.", "error");
            setTimeout(() => window.location.href = "crear-asistente.php", 2000);
          } 
          else {
            mostrarModal("Error desconocido: " + resp, "advertencia");
          }
        })
        .catch(() => {
          mostrarModal("Error al conectar con el servidor.", "error");
        });
    }

    function reenviarCodigo() {
      mostrarModal("📧 Se ha reenviado el código a tu correo.", "info");
    }
  </script>
</body>
</html>
