/* ===============================
   🌐 SCRIPT GLOBAL PUMFEST
   Archivo: global.js
   =============================== */

// ✅ Activar animación suave al cargar la página
window.addEventListener("load", () => {
  document.body.classList.add("cargado");
});

// ✅ Animación de transición al salir (opcional y elegante)
document.querySelectorAll("a").forEach(enlace => {
  enlace.addEventListener("click", (e) => {
    const url = enlace.getAttribute("href");

    // Ignorar enlaces vacíos o con "#"
    if (!url || url.startsWith("#") || url.startsWith("javascript:")) return;

    e.preventDefault();

    // Añadir animación de salida
    document.body.classList.remove("cargado");
    document.body.style.opacity = "0";
    document.body.style.transition = "opacity 0.4s ease";

    setTimeout(() => {
      window.location.href = url;
    }, 400);
  });
});

// ✅ (Opcional) Efecto de scroll suave entre secciones
document.querySelectorAll('a[href^="#"]').forEach(ancla => {
  ancla.addEventListener("click", function(e) {
    e.preventDefault();
    const seccion = document.querySelector(this.getAttribute("href"));
    if (seccion) {
      seccion.scrollIntoView({ behavior: "smooth" });
    }
  });
});
