// ===============================
// 🧾 MODAL DE TÉRMINOS Y CONDICIONES
// ===============================

// Obtener elementos
const modal = document.getElementById('modal-terminos');
const btnMostrar = document.getElementById('mostrar-terminos');
const btnAceptar = document.getElementById('aceptar-modal');
const checkbox = document.getElementById('aceptar-terminos');

// Abrir modal al hacer click en el texto
if (btnMostrar && modal) {
  btnMostrar.addEventListener('click', () => {
    modal.style.display = 'flex';
  });
}

// Aceptar términos y cerrar modal
if (btnAceptar && modal && checkbox) {
  btnAceptar.addEventListener('click', () => {
    modal.style.display = 'none';
    checkbox.checked = true; // marca el checkbox automáticamente
  });
}

// Cerrar modal si hace click fuera del contenido
window.addEventListener('click', (e) => {
  if (e.target === modal) {
    modal.style.display = 'none';
  }
});

// ===============================
// 📱 FORMATEAR TELÉFONO AUTOMÁTICAMENTE
// ===============================

const telInput = document.getElementById("telefono");

if (telInput) {
  telInput.addEventListener("input", (e) => {
    let value = e.target.value.replace(/\D/g, ""); // solo números

    // Asegurar que empiece con "57" (Colombia)
    if (!value.startsWith("57")) value = "57" + value;

    // Dar formato: +57 322 227 8027
    let formatted = "+57";
    if (value.length > 2) formatted += " " + value.slice(2, 5);
    if (value.length > 5) formatted += " " + value.slice(5, 8);
    if (value.length > 8) formatted += " " + value.slice(8, 12);

    e.target.value = formatted.trim();
  });

  // Mantener formato al enviar
  const form = document.querySelector("form");
  if (form) {
    form.addEventListener("submit", () => {
      if (!telInput.value.startsWith("+57")) {
        let value = telInput.value.replace(/\D/g, "");
        if (!value.startsWith("57")) value = "57" + value;
        let formatted = "+57";
        if (value.length > 2) formatted += " " + value.slice(2, 5);
        if (value.length > 5) formatted += " " + value.slice(5, 8);
        if (value.length > 8) formatted += " " + value.slice(8, 12);
        telInput.value = formatted.trim();
      }
    });
  }
}

// ===============================
// 🧩 MODAL DE TÉRMINOS Y CONDICIONES
// ===============================
document.addEventListener("DOMContentLoaded", () => {
  const abrirTerminos = document.getElementById("abrirTerminos");
  const modal = document.getElementById("modal-terminos");
  const cerrarModal = document.getElementById("cerrarModal");
  const aceptarTerminos = document.getElementById("aceptarTerminos");
  const checkTerminos = document.getElementById("terminos");

  // Abrir el modal al hacer clic en el texto
  abrirTerminos.addEventListener("click", (e) => {
    e.preventDefault();
    modal.style.display = "flex";
  });

  // Cerrar al hacer clic en la X
  cerrarModal.addEventListener("click", () => {
    modal.style.display = "none";
  });

  // Aceptar términos: marcar el checkbox y cerrar modal
  aceptarTerminos.addEventListener("click", () => {
    checkTerminos.checked = true;
    modal.style.display = "none";
  });

  // Cerrar si se hace clic fuera del contenido
  window.addEventListener("click", (e) => {
    if (e.target === modal) {
      modal.style.display = "none";
    }
  });
});
