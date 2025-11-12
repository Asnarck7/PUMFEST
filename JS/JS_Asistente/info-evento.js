console.log("info-evento.js cargado correctamente ✅");

const userBtn = document.getElementById("userBtn");
const dropdown = document.getElementById("userDropdown");

if (userBtn) {
  userBtn.addEventListener("click", (e) => {
    e.stopPropagation();
    dropdown.classList.toggle("show");
  });

  window.addEventListener("click", (e) => {
    if (!dropdown.contains(e.target) && !userBtn.contains(e.target)) {
      dropdown.classList.remove("show");
    }
  });
}
