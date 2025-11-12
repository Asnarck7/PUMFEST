// === mensaje.js ===
// Muestra un modal centrado con mensaje y color según el tipo
// Tipos: "exito", "error", "advertencia", "info"

function mostrarModal(mensaje, tipo = "info") {
  // 🧹 Eliminar cualquier modal anterior
  const anterior = document.querySelector('.overlay');
  if (anterior) anterior.remove();

  // 🎨 Colores según tipo
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
    case "info":
      color = "#ff6600";
      titulo = "ℹ️ Información";
      break;
  }

  // 🪟 Crear overlay principal
  const overlay = document.createElement('div');
  overlay.className = 'overlay';
  overlay.style.display = 'flex';

  // 💬 Contenido del modal
  overlay.innerHTML = `
    <div class="modal" style="border-top: 4px solid ${color}">
      <p style="color: ${color}; font-weight: 600; margin-bottom: 10px;">${titulo}</p>
      <p style="color: #fff; margin-bottom: 15px;">${mensaje}</p>
      <button id="btnAceptar" style="
        background: ${color};
        border: none;
        color: white;
        padding: 10px 20px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
      ">Aceptar</button>
    </div>
  `;

  // Agregar al documento
  document.body.appendChild(overlay);

  // 🔘 Cerrar modal al hacer clic en "Aceptar"
  document.getElementById('btnAceptar').onclick = () => {
    overlay.remove();
  };

  // ⏳ Cierre automático tras 5 segundos
  setTimeout(() => {
    if (document.body.contains(overlay)) overlay.remove();
  }, 5000);
}
