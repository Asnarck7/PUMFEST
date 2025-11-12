document.addEventListener("DOMContentLoaded", () => {
  const btnEnviarCodigo = document.getElementById("btnEnviarCodigo");
  const modalEditar = document.getElementById("modalEditar");
  const modalCodigo = document.getElementById("modalCodigo");
  const cerrarCodigo = document.getElementById("cerrarCodigo");
  const formCambiar = document.getElementById("formCambiarContrasena");

  if (!btnEnviarCodigo) return;

  // 🟡 Enviar código de verificación
  btnEnviarCodigo.addEventListener("click", async () => {
    const confirmar = await Swal.fire({
      title: "¿Estás seguro?",
      text: "Se enviará un código de verificación a tu correo para cambiar la contraseña.",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Sí, enviar",
      cancelButtonText: "Cancelar",
      background: "#1e1a24",
      color: "#ffb84d",
      confirmButtonColor: "#ffb84d",
      cancelButtonColor: "#555",
    });

    if (!confirmar.isConfirmed) return;

    try {
      modalEditar.style.display = "none";

      // ✅ Enviar solicitud CON sesión
        const resp = await fetch("../../PHP/PHP_Asistente/enviarCodigoCambioContrasena.php", {

        method: "POST",
        credentials: "include" // <<--- Mantiene la sesión
      });

      const data = await resp.json();
      console.log("RESPUESTA PHP:", data);

      if (data.status === "ok") {
        Swal.fire({
          icon: "success",
          title: "✅ Código enviado",
          text: "Revisa tu correo electrónico.",
          background: "#1e1a24",
          color: "#ffb84d",
          confirmButtonColor: "#ffb84d",
        });
        modalCodigo.style.display = "flex";
      } else if (data.msg?.includes("sin_sesion")) {
        Swal.fire({
          icon: "error",
          title: "⚠️ Sesión expirada",
          text: "Vuelve a iniciar sesión.",
          background: "#1e1a24",
          color: "#ffb84d",
          confirmButtonColor: "#ffb84d",
        }).then(() => (window.location.href = "iniciar-asistente.php"));
      } else {
        Swal.fire({
          icon: "error",
          title: "❌ Error",
          text: data.msg || "No se pudo enviar el correo.",
          background: "#1e1a24",
          color: "#ffb84d",
          confirmButtonColor: "#ffb84d",
        });
      }
    } catch (err) {
      console.error(err);
      Swal.fire({
        icon: "error",
        title: "⚠️ Error",
        text: "No se pudo conectar con el servidor.",
        background: "#1e1a24",
        color: "#ffb84d",
        confirmButtonColor: "#ffb84d",
      });
    }
  });

  // 🧩 Cerrar modal de código
  cerrarCodigo.addEventListener("click", () => {
    modalCodigo.style.display = "none";
  });

  // 💫 Enviar formulario de cambio de contraseña
  formCambiar.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = new FormData(formCambiar);

    try {
      // ✅ Enviar con sesión activa
      const resp = await fetch("../../PHP/PHP_Asistente/verificarCodigoCambioContrasena.php", {

        method: "POST",
        body: formData,
        credentials: "include" // <<--- Aquí también
      });

      const txt = await resp.text();
      console.log("RESPUESTA PHP:", txt);

      if (txt.includes("actualizada")) {
        modalCodigo.style.display = "none";
        Swal.fire({
          title: "¡Contraseña actualizada!",
          text: "Tu nueva contraseña se guardó correctamente.",
          imageUrl: "../../LogoPUMFEST/PumFestLISTO.png",
          imageWidth: 120,
          imageHeight: 120,
          background: "#1f1b29",
          color: "#ffb84d",
          confirmButtonColor: "#ffb84d",
          confirmButtonText: "Entendido",
        });
      } else if (txt.includes("no_coinciden")) {
        Swal.fire("Las contraseñas no coinciden", "", "warning");
      } else if (txt.includes("invalido")) {
        Swal.fire("Código inválido", "Verifica e intenta nuevamente", "error");
      } else if (txt.includes("expirado")) {
        Swal.fire("Código expirado", "Solicita uno nuevo.", "info");
      } else if (txt.includes("sin_sesion")) {
        Swal.fire("⚠️ Sesión expirada", "Vuelve a iniciar sesión.", "error")
          .then(() => (window.location.href = "iniciar-asistente.php"));
      } else {
        Swal.fire("Error inesperado", txt, "error");
      }
    } catch (err) {
      console.error("Error:", err);
      Swal.fire("Error de conexión", "No se pudo contactar con el servidor.", "error");
    }
  });
});