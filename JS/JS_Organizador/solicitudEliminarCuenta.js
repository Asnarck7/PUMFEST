/**
 * solicitudEliminarCuenta.js
 * Controla la verificación de contraseña y redirección
 * para que el organizador solicite la eliminación de su cuenta.
 */

document.addEventListener("DOMContentLoaded", () => {
  console.log("⚙️ solicitudEliminarCuenta.js cargado correctamente");

  const btnSolicitud = document.getElementById("solicitarEliminarCuenta");
  if (!btnSolicitud) return;

  btnSolicitud.addEventListener("click", async () => {
    // ⚠️ Confirmar intención
    const confirmar = await Swal.fire({
      title: "🧾 Solicitud de eliminación",
      text: "¿Estás seguro de que deseas solicitar la eliminación de tu cuenta? Esta acción no se puede deshacer.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Sí, continuar",
      cancelButtonText: "Cancelar",
      confirmButtonColor: "#16a34a",
      cancelButtonColor: "#ef4444",
    });

    if (!confirmar.isConfirmed) return;

    // 🔒 Solicitar contraseña del organizador
    const { value: password } = await Swal.fire({
      title: "🔐 Verificación de identidad",
      text: "Por favor, ingresa tu contraseña para continuar.",
      input: "password",
      inputPlaceholder: "Tu contraseña",
      inputAttributes: { maxlength: 50, autocapitalize: "off", autocorrect: "off" },
      showCancelButton: true,
      confirmButtonText: "Verificar",
      cancelButtonText: "Cancelar",
      confirmButtonColor: "#16a34a",
      cancelButtonColor: "#ef4444",
    });

    if (!password) return;

    // 🧠 Verificar contraseña del organizador
    try {
      const resp = await fetch("../../PHP/PHP_Organizador/verificarPasswordOrganizador.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `password=${encodeURIComponent(password)}`,
      });

      let data;
      try {
        data = await resp.json();
      } catch (e) {
        Swal.fire("⚠️ Error", "Respuesta inesperada del servidor.", "error");
        return;
      }

      if (data.status !== "ok") {
        Swal.fire("❌ Error", data.mensaje || "Contraseña incorrecta.", "error");
        return;
      }

      // ✅ Verificación exitosa → redirigir al formulario
      Swal.fire({
        title: "✅ Verificación exitosa",
        text: "Ahora podrás llenar el formulario para enviar tu solicitud de eliminación.",
        icon: "success",
        confirmButtonText: "Continuar",
        confirmButtonColor: "#facc15",
      }).then(() => {
        window.location.href = "../../PHP/PHP_Organizador/solicitudOrganizador/solicitudEliminarCuenta.php";
      });

    } catch (error) {
      console.error("❌ Error en la solicitud:", error);
      Swal.fire("⚠️ Error", "No se pudo contactar al servidor.", "error");
    }
  });
});


document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("formEliminarCuenta");

  if (!form) return;

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const motivo = document.getElementById("motivo").value.trim();

    if (!motivo) {
      Swal.fire("⚠️ Error", "Por favor escribe un motivo.", "warning");
      return;
    }

    try {
      const resp = await fetch("", {
        method: "POST",
        body: new URLSearchParams({ motivo }),
      });

      const data = await resp.json();

      if (data.status === "ok") {
        Swal.fire("✅ Éxito", data.mensaje, "success").then(() => {
          window.location.href = "../panelOrganizador.php";
        });
      } else {
        Swal.fire("❌ Error", data.mensaje, "error");
      }
    } catch (err) {
      Swal.fire("💥 Error", "No se pudo enviar la solicitud.", "error");
    }
  });
});


