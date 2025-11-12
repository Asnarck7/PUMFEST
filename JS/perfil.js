document.addEventListener("DOMContentLoaded", () => {
  // ✅ LOGO → Volver al inicio
  const logoInicio = document.getElementById("irInicio");
  if (logoInicio) {
    logoInicio.addEventListener("click", () => {
      window.location.href = "index.php?skipVideo=1";
    });
  }

  // ✅ MODAL (abrir / cerrar)
  const modal = document.getElementById("miModal");
  const abrir = document.getElementById("abrirModal");
  const cerrar = document.getElementById("cerrarModal");

  if (abrir && cerrar && modal) {
    abrir.addEventListener("click", () => (modal.style.display = "flex"));
    cerrar.addEventListener("click", () => (modal.style.display = "none"));
    window.addEventListener("click", (e) => {
      if (e.target === modal) modal.style.display = "none";
    });
  }

  // ✅ MODAL EDITAR PERFIL
const modalEditar = document.getElementById("modalEditar");
const abrirEditar = document.getElementById("abrirEditar");
const cerrarEditar = document.getElementById("cerrarEditar");

if (abrirEditar && cerrarEditar && modalEditar) {
  abrirEditar.addEventListener("click", () => (modalEditar.style.display = "flex"));
  cerrarEditar.addEventListener("click", () => (modalEditar.style.display = "none"));
  window.addEventListener("click", (e) => {
    if (e.target === modalEditar) modalEditar.style.display = "none";
  });
}

});


