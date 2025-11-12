// ================================
//  Guardar información del evento
// ================================
function guardarInfo(evento, fecha) {
  localStorage.setItem("evento", evento);
  localStorage.setItem("fecha", fecha);
  window.location.href = "PHP_Asistente/entradas-precio.php";
  

}

// ================================
//  Control de menú de usuario
// ================================

document.addEventListener("DOMContentLoaded", () => {
  const userBtn = document.getElementById("userBtn");
  const dropdown = document.getElementById("userDropdown");

  if (userBtn && dropdown) {
    userBtn.addEventListener("click", () => {
      dropdown.classList.toggle("show");
    });

    // Cerrar el menú si se hace clic fuera del mismo
    window.addEventListener("click", (e) => {
      if (!e.target.closest(".user-menu")) {
        dropdown.classList.remove("show");
      }
    });
  }
});


