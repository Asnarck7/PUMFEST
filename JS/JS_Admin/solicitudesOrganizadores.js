document.addEventListener("DOMContentLoaded", () => {
  console.log("⚙️ solicitudesOrganizadores.js cargado correctamente");

  const botones = document.querySelectorAll(".btn-accion");

  botones.forEach((btn) => {
    btn.addEventListener("click", async () => {
      const id = btn.dataset.id;
      const esAprobar = btn.classList.contains("aprobar");
      const accion = esAprobar ? "aprobar_solicitud" : "rechazar_solicitud";
      const mensaje = esAprobar
        ? "¿Deseas aprobar esta solicitud y eliminar la cuenta del organizador?"
        : "¿Deseas rechazar esta solicitud?";

      const confirmar = await Swal.fire({
        title: "Confirmar acción",
        text: mensaje,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: esAprobar ? "#16a34a" : "#dc2626",
        cancelButtonColor: "#6b7280",
        confirmButtonText: "Sí, continuar",
        cancelButtonText: "Cancelar",
      });

      if (!confirmar.isConfirmed) return;

      try {
        const resp = await fetch("accionesEvento.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: `accion=${accion}&id=${encodeURIComponent(id)}`,
        });

        const data = await resp.json();

        if (data.status === "ok") {
          Swal.fire("✅ Éxito", data.mensaje, "success").then(() => location.reload());
        } else {
          Swal.fire("❌ Error", data.mensaje || "No se pudo procesar la solicitud.", "error");
        }
      } catch (err) {
        Swal.fire("💥 Error", "No se pudo contactar al servidor.", "error");
      }
    });
  });
});