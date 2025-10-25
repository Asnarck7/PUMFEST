let zonaSeleccionada = null;
let precioZona = 0;
let cantidad = 1;

function seleccionarZona(zona, precio) {
  zonaSeleccionada = zona;
  precioZona = precio;
  cantidad = 1;

  // Resalta la zona seleccionada
  document.querySelectorAll(".precio-item").forEach(item => item.classList.remove("activa"));
  event.currentTarget.classList.add("activa");

  // Actualiza cantidad y total
  document.getElementById("qty").value = cantidad;
  actualizarTotal();
}

function cambiar(valor) {
  if (!zonaSeleccionada) {
    mostrarNotificacion("⚠️ Primero selecciona una zona.");
    return;
  }

  cantidad += valor;
  if (cantidad < 1) cantidad = 1;
  if (cantidad > 5) cantidad = 5;

  document.getElementById("qty").value = cantidad;
  actualizarTotal();
}

function actualizarTotal() {
  const total = precioZona * cantidad;
  document.getElementById("total").textContent = "Total: $" + total.toLocaleString("es-CO");
}

function guardarCompra() {
  if (!zonaSeleccionada) {
    mostrarNotificacion("⚠️ Por favor selecciona una zona antes de continuar.");
    return;
  }

  const total = precioZona * cantidad;

  // Guardar datos en localStorage
  localStorage.setItem("zona", zonaSeleccionada);
  localStorage.setItem("precioZona", precioZona);
  localStorage.setItem("cantidad", cantidad);
  localStorage.setItem("total", total);

  // Redirigir a la página de compra
  window.location.href = "../PHP/comprar-ticket.php";
}

/* ==========================================================
   🔔 NUEVA FUNCIÓN DE NOTIFICACIÓN ANIMADA
========================================================== */
function mostrarNotificacion(texto) {
  let notificacion = document.getElementById("notificacion");

  // Si no existe, la creamos dinámicamente
  if (!notificacion) {
    notificacion = document.createElement("div");
    notificacion.id = "notificacion";
    notificacion.className = "notificacion";
    notificacion.innerHTML = `
      <p id="mensaje">${texto}</p>
      <img src="../IMG/avatar.png" alt="Avatar" class="avatar">
    `;
    document.body.appendChild(notificacion);
  } else {
    document.getElementById("mensaje").textContent = texto;
  }

  // Mostrar animación
  notificacion.classList.add("mostrar");

  // Ocultar después de 3 segundos
  setTimeout(() => {
    notificacion.classList.remove("mostrar");
  }, 2500);
}
