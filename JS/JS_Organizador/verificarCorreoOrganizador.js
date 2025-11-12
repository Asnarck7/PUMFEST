// === verificarCorreoOrganizador.js ===
// Muestra un modal bonito en el centro y gestiona la verificación del correo del organizador.

// ==========================
// 🪟 FUNCIÓN PARA MOSTRAR EL MODAL
// ==========================
function mostrarModal(mensaje, tipo = "info") {
  // Eliminar modal anterior
  const anterior = document.querySelector('.overlay');
  if (anterior) anterior.remove();

  // Colores y títulos según tipo
  let color = "#ff6600";
  let titulo = "ℹ️ Información";

  switch (tipo) {
    case "exito":
      color = "#28a745";
      titulo = "✅ Éxito";
      break;
    case "error":
      color = "#dc3545";
      titulo = "❌ Error";
      break;
    case "advertencia":
      color = "#ffc107";
      titulo = "⚠️ Atención";
      break;
  }

  // Crear overlay principal
  const overlay = document.createElement('div');
  overlay.className = 'overlay';
  overlay.style.display = 'flex';

  // Contenido del modal
  overlay.innerHTML = `
    <div class="modal" style="border-top: 4px solid ${color}">
      <p style="color: ${color}; font-weight: 600; margin-bottom: 10px;">${titulo}</p>
      <p style="color: #fff; margin-bottom: 15px;">${mensaje}</p>
      <button id="btnAceptar" style="
        background:${color};
        border:none;
        color:white;
        padding:10px 20px;
        border-radius:6px;
        cursor:pointer;
        font-weight:600;
      ">Aceptar</button>
    </div>
  `;

  document.body.appendChild(overlay);
  document.getElementById('btnAceptar').onclick = () => overlay.remove();

  // Cierre automático tras 5 segundos
  setTimeout(() => {
    if (document.body.contains(overlay)) overlay.remove();
  }, 5000);
}

// ==========================
// 🚀 ENVIAR CÓDIGO AUTOMÁTICAMENTE AL CARGAR
// ==========================
document.addEventListener("DOMContentLoaded", () => {
  fetch("enviarCodigoOrganizador.php", { method: "POST" })
    .then(r => r.json())
    .then(d => {
      if (d.status === "ok") {
        mostrarModal("📧 Código enviado correctamente a tu correo.", "exito");
      } else {
        mostrarModal("No se pudo enviar el código: " + (d.msg || "Error desconocido"), "error");
      }
    })
    .catch(() => mostrarModal("Error al conectar con el servidor.", "error"));
});

// ==========================
// ✅ VERIFICAR CÓDIGO
// ==========================
function verificarCodigo() {
  const codigo = document.getElementById("codigo").value.trim();
  if (codigo === "") {
    mostrarModal("Por favor ingresa el código de verificación.", "advertencia");
    return;
  }

  const formData = new FormData();
  formData.append("codigo", codigo);

  fetch("verificarCodigoOrganizador.php", {
    method: "POST",
    body: formData
  })
  .then(r => r.text())
  .then(data => {
    data = data.trim();

    if (data === "verificado") {
      mostrarModal("✅ ¡Correo verificado! Espera aprobación del administrador.", "exito");
      setTimeout(() => window.location.href = "loginOrganizador.php", 2500);
    } 
    else if (data === "invalido") {
      mostrarModal("❌ Código incorrecto. Intenta de nuevo.", "error");
    }
    else if (data === "expirado") {
      mostrarModal("⚠️ El código ha expirado. Solicita uno nuevo.", "advertencia");
    }
    else {
      mostrarModal("⚠️ Error inesperado: " + data, "error");
    }
  })
  .catch(() => mostrarModal("Error de conexión con el servidor.", "error"));
}

// ==========================
// 🔁 REENVIAR CÓDIGO
// ==========================
function reenviarCodigo() {
  fetch("enviarCodigoOrganizador.php", { method: "POST" })
    .then(r => r.json())
    .then(d => {
      if (d.status === "ok") {
        mostrarModal("📩 Código reenviado correctamente.", "info");
      } else {
        mostrarModal("⚠️ No se pudo reenviar el código: " + (d.msg || ""), "advertencia");
      }
    })
    .catch(() => mostrarModal("Error al intentar reenviar el código.", "error"));
}
