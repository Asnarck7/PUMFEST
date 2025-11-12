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
})