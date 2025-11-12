// ================================
//  CARGAR DATOS GUARDADOS
// ================================
document.addEventListener("DOMContentLoaded", () => {
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
    ? `$${parseInt(total).toLocaleString("es-CO")}`
    : "$0";
});

// ================================
//  FINALIZAR COMPRA (enviar al servidor)
// ================================
async function finalizarCompra() {
  const evento_id = localStorage.getItem("evento_id");
  const categoria_id = localStorage.getItem("categoria_id");
  const cantidad = localStorage.getItem("cantidad");

  // ✅ Validar datos antes de enviar
  if (!evento_id || !categoria_id || !cantidad) {
    Swal.fire({
      icon: "warning",
      title: "Faltan datos",
      text: "⚠️ Faltan datos de la compra. Intenta nuevamente.",
      confirmButtonColor: "#ff6b35",
    });
    return;
  }

  console.log("🟢 Enviando datos:", { evento_id, categoria_id, cantidad });

  try {
    // ✅ Enviar datos al backend
    const respuesta = await fetch("../PHP_Asistente/finalizarCompra.php", {
      method: "POST",
      body: new URLSearchParams({
        evento_id,
        categoria_id,
        cantidad,
      }),
    });

    const texto = await respuesta.text();
    let data;

    try {
      data = JSON.parse(texto);
    } catch {
      console.error("❌ Respuesta no válida del servidor:", texto);
      Swal.fire({
        icon: "error",
        title: "Error del servidor",
        html:
          "No se pudo completar la compra.<br><br>" +
          "<small>Revisa la consola (F12) para más detalles.</small>",
        confirmButtonColor: "#ff6b35",
      });
      return;
    }

    // ✅ Respuesta correcta
    if (data.status === "ok") {
      Swal.fire({
        icon: "success",
        title: "¡Compra completada!",
        text: data.mensaje,
        confirmButtonColor: "#ff6b35",
      }).then(() => {
        localStorage.clear();
        window.location.href = "perfilAsistente.php"; // direccion
      });
    } else {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: data.mensaje,
        confirmButtonColor: "#ff6b35",
      });
    }
  } catch (err) {
    console.error("🚨 Error de red o PHP:", err);
    Swal.fire({
      icon: "error",
      title: "Error de conexión",
      text:
        "No se pudo contactar con el servidor. Verifica tu conexión o el backend.",
      confirmButtonColor: "#ff6b35",
    });
  }
}