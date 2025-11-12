// ==============================
// 🎯 LOGIN ORGANIZADOR
// Archivo: loginCuentaOrganizador.js
// ==============================

document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector(".login-form");
  const loader = document.getElementById("loader");

  if (!form) return; // Si no hay formulario, salimos

  // Enviar formulario con animación de carga
  form.addEventListener("submit", (e) => {
    e.preventDefault(); // Evita el envío tradicional
    loader.style.display = "flex"; // Mostrar loader inmediatamente

    const datos = new FormData(form);

    fetch(form.action, {
      method: "POST",
      body: datos
    })
      .then(res => res.json())
      .then(data => {
        loader.style.display = "none"; // Ocultar loader

        if (data.ok) {
          // Mostrar loader durante 2 segundos antes de redirigir
          setTimeout(() => {
            window.location.href = data.redirect;
          }, 2000);
        } else {
          // Mostrar mensaje de error usando la función global
          mostrarModal(data.mensaje || "❌ Error desconocido. Intenta nuevamente.", "error");
        }
      })
      .catch(() => {
        loader.style.display = "none";
        mostrarModal("Error de conexión con el servidor.", "error");
      });
  });
});
