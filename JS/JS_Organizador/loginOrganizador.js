// ==============================
// 🌟 MODAL DE NOTIFICACIÓN ESTILO MODERNO
// ==============================
function mostrarModal(mensaje, tipo = "info") {
  const existente = document.querySelector(".overlay");
  if (existente) existente.remove();

  let color = "#00bcd4";
  let titulo = "ℹ️ Información";
  let icono = "💬";

  switch (tipo) {
    case "exito":
      color = "#00c853";
      titulo = "✅ Éxito";
      icono = "✅";
      break;
    case "error":
      color = "#ff1744";
      titulo = "❌ Error";
      icono = "❌";
      break;
    case "advertencia":
      color = "#ffab00";
      titulo = "⚠️ Atención";
      icono = "⚠️";
      break;
  }

  // Fondo translúcido y desenfocado
  const overlay = document.createElement("div");
  overlay.className = "overlay";
  overlay.style.cssText = `
    position: fixed;
    inset: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    background: rgba(15, 15, 15, 0.6);
    backdrop-filter: blur(6px);
    z-index: 9999;
    animation: fadeIn 0.3s ease forwards;
  `;

  // Caja principal con estilo glassmorphism + glow
  const modal = document.createElement("div");
  modal.className = "modal";
  modal.style.cssText = `
    background: rgba(30, 30, 30, 0.9);
    border-radius: 18px;
    padding: 30px 35px;
    max-width: 380px;
    text-align: center;
    color: #fff;
    border: 1px solid ${color}33;
    box-shadow: 0 0 25px ${color}55, inset 0 0 8px #000;
    backdrop-filter: blur(10px);
    transform: scale(0.8);
    opacity: 0;
    animation: modalShow 0.45s cubic-bezier(0.22, 1, 0.36, 1) forwards;
  `;

  // Contenido con ícono y texto
  modal.innerHTML = `
    <div style="
      font-size: 3rem;
      color: ${color};
      margin-bottom: 10px;
      text-shadow: 0 0 10px ${color}55;
      animation: iconPop 0.5s ease forwards;
    ">${icono}</div>

    <h3 style="
      font-size: 1.4rem;
      font-weight: 700;
      color: ${color};
      margin-bottom: 10px;
      letter-spacing: 0.5px;
    ">${titulo}</h3>

    <p style="
      font-size: 1.05rem;
      margin-bottom: 25px;
      color: #e0e0e0;
      line-height: 1.4;
    ">${mensaje}</p>

    <button id="btnAceptar" style="
      background: linear-gradient(135deg, ${color}, ${color}aa);
      border: none;
      color: white;
      padding: 12px 28px;
      border-radius: 10px;
      cursor: pointer;
      font-weight: 600;
      font-size: 1rem;
      box-shadow: 0 0 15px ${color}66;
      transition: all 0.3s ease;
    ">Aceptar</button>
  `;

  overlay.appendChild(modal);
  document.body.appendChild(overlay);

  // Efecto hover del botón
  const btnAceptar = modal.querySelector("#btnAceptar");
  btnAceptar.addEventListener("mouseover", () => {
    btnAceptar.style.transform = "scale(1.07)";
    btnAceptar.style.boxShadow = `0 0 20px ${color}`;
  });
  btnAceptar.addEventListener("mouseout", () => {
    btnAceptar.style.transform = "scale(1)";
    btnAceptar.style.boxShadow = `0 0 15px ${color}66`;
  });
  btnAceptar.addEventListener("click", () => overlay.remove());
  overlay.addEventListener("click", (e) => {
    if (e.target === overlay) overlay.remove();
  });

  // Cierre automático
  setTimeout(() => {
    if (document.body.contains(overlay)) {
      modal.style.animation = "modalHide 0.4s ease forwards";
      setTimeout(() => overlay.remove(), 400);
    }
  }, 5000);
}

// ==============================
// ✨ ANIMACIONES CSS
// ==============================
const estiloAnimaciones = document.createElement("style");
estiloAnimaciones.textContent = `
@keyframes fadeIn {
  from { opacity: 0; } to { opacity: 1; }
}
@keyframes modalShow {
  0% { transform: scale(0.7) translateY(20px); opacity: 0; }
  60% { transform: scale(1.05) translateY(-8px); opacity: 1; }
  100% { transform: scale(1) translateY(0); opacity: 1; }
}
@keyframes modalHide {
  from { transform: scale(1); opacity: 1; }
  to { transform: scale(0.8); opacity: 0; }
}
@keyframes iconPop {
  0% { transform: scale(0); opacity: 0; }
  70% { transform: scale(1.3); opacity: 1; }
  100% { transform: scale(1); opacity: 1; }
}
`;
document.head.appendChild(estiloAnimaciones);



// ==============================
// 🚀 LOGIN SCRIPT PRINCIPAL
// ==============================
document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector(".login-form");
  const alerta = document.getElementById("alerta");
  const alertaTexto = document.getElementById("alerta-texto");
  const cerrarAlerta = document.getElementById("cerrarAlerta");
  const loader = document.getElementById("loader");

  // ✅ Verificar que el formulario exista antes de seguir
  if (!form) {
    console.warn("⚠️ No se encontró el formulario con la clase .login-form");
    return;
  }

  // ✅ Mostrar alerta simple (fallback por si no hay modal)
  function mostrarAlerta(mensaje) {
    if (alerta && alertaTexto) {
      alertaTexto.textContent = mensaje;
      alerta.style.display = "flex";
    } else {
      console.log(mensaje);
    }
  }

  if (cerrarAlerta) {
    cerrarAlerta.addEventListener("click", () => {
      alerta.style.display = "none";
    });
  }

  // ✅ Enviar formulario con animación de carga
  form.addEventListener("submit", (e) => {
    e.preventDefault();
    if (loader) loader.style.display = "flex";

    const datos = new FormData(form);

    fetch(form.action, {
      method: "POST",
      body: datos
    })
      .then(res => res.json())
      .then(data => {
        if (loader) loader.style.display = "none";

        if (data.ok) {
          mostrarModal("Inicio de sesión exitoso. Redirigiendo...", "exito");
          setTimeout(() => {
            window.location.href = data.redirect;
          }, 2000);
        } else {
          mostrarModal(data.mensaje || "❌ Error desconocido. Intenta nuevamente.", "error");
        }
      })
      .catch(() => {
        if (loader) loader.style.display = "none";
        mostrarModal("Error de conexión con el servidor.", "error");
      });
  });
});


// ==============================
// 🚀 Mostrar mensaje desde PHP (opcional)
// ==============================
window.addEventListener("DOMContentLoaded", () => {
  const body = document.querySelector("body");
  const msg = body.dataset.mensaje;
  const tipo = body.dataset.tipo;

  if (msg) {
    mostrarModal(msg, tipo || "info");
  }
});