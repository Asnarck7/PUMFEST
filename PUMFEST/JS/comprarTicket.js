// ================================
//  COMPRAR TICKET - FUNCIONALIDAD
// ================================

// 🟢 Cargar datos guardados en localStorage
document.getElementById("evento").textContent =
  localStorage.getItem("evento") || "No definido";
document.getElementById("fecha").textContent =
  localStorage.getItem("fecha") || "No definida";
document.getElementById("zona").textContent =
  localStorage.getItem("zona") || "No seleccionada";
document.getElementById("cantidad").textContent =
  localStorage.getItem("cantidad") || "0";

const total = localStorage.getItem("total");
document.getElementById("total").textContent = total
  ? `$${parseInt(total).toLocaleString()}`
  : "$0";

// 🟣 Función que finaliza la compra
function finalizarCompra() {
  alert("✅ Compra realizada con éxito. ¡Disfruta el evento!");
  localStorage.clear(); // Limpia los datos del carrito
  window.location.href = "index.php"; // Redirige al inicio
}
