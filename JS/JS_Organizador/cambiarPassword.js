document.addEventListener("DOMContentLoaded", () => {
  console.log("✅ cambiarPassword.js cargado correctamente");

  const btnCambiarPass = document.getElementById("btnCambiarPass"); // Botón principal en el formulario
  const overlay = document.getElementById("overlayCambioPass");
  const cerrarOverlay = document.getElementById("cerrarOverlay");
  const btnEnviarCodigo = document.getElementById("enviarCodigo"); // Botón dentro del overlay
  const divVerificar = document.getElementById("verificarCodigo");
  const btnConfirmar = document.getElementById("confirmarCambio");

  // 🟢 Mostrar overlay
  btnCambiarPass.addEventListener("click", () => {
    overlay.style.display = "flex";
  });

  // 🔴 Cerrar overlay
  cerrarOverlay.addEventListener("click", () => {
    overlay.style.display = "none";
  });

  // 🟡 Enviar código al correo del organizador
  btnEnviarCodigo.addEventListener("click", async () => {
    const confirmar = await Swal.fire({
      title: "¿Enviar código?",
      text: "Se enviará un código de verificación a tu correo registrado.",
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
      const resp = await fetch("../../PHP/PHP_Organizador/enviarCodigoCambioPassword.php", {
        method: "POST",
        credentials: "include",
      });

      const text = await resp.text();
      console.log("RESPUESTA RAW:", text);

      let data;
      try {
        data = JSON.parse(text);
      } catch (e) {
        console.error("⚠️ Respuesta no válida JSON:", text);
        Swal.fire("Error del servidor", "Respuesta inesperada del servidor.", "error");
        return;
      }

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
        divVerificar.classList.remove("hidden");
      } else if (data.status === "espera") {
        Swal.fire("⚠️ Espera unos minutos", data.msg, "info");
      } else if (data.status === "error" && data.msg === "sin_sesion") {
        Swal.fire("⚠️ Sesión expirada", "Vuelve a iniciar sesión.", "error")
          .then(() => (window.location.href = "loginOrganizador.php"));
      } else {
        Swal.fire("❌ Error", data.msg || "No se pudo enviar el correo.", "error");
      }
    } catch (err) {
      console.error("Error:", err);
      Swal.fire("Error de conexión", "No se pudo contactar con el servidor.", "error");
    }
  });

  // 💾 Confirmar cambio de contraseña
  btnConfirmar.addEventListener("click", async () => {
    const codigo = document.getElementById("codigoInput").value.trim();
    const nuevaPass = document.getElementById("nuevaPassword").value.trim();
    const confirmarPass = document.getElementById("confirmarPassword").value.trim(); // 🆕 Campo nuevo

    if (!codigo || !nuevaPass || !confirmarPass) {
      Swal.fire("⚠️ Campos vacíos", "Completa todos los campos.", "warning");
      return;
    }

    // 🆕 Verificar contraseñas
    if (nuevaPass !== confirmarPass) {
      Swal.fire("❌ Error", "Las contraseñas no coinciden. Intenta nuevamente.", "error");
      return;
    }

    try {
      const formData = new FormData();
      formData.append("codigo", codigo);
      formData.append("nueva_contrasena", nuevaPass);

      const resp = await fetch("../../PHP/PHP_Organizador/verificarCodigoPasswordNuevo.php", {
        method: "POST",
        body: formData,
        credentials: "include",
      });

      const txt = await resp.text();
      console.log("RESPUESTA PHP:", txt);

      if (txt.includes("actualizada")) {
        overlay.style.display = "none";
        Swal.fire({
          title: "¡Contraseña actualizada!",
          text: "Tu nueva contraseña se guardó correctamente.",
          imageUrl: "../../LogoPUMFEST/PumFestLISTO.png",
          imageWidth: 120,
          imageHeight: 120,
          background: "#1f1b29",
          color: "#ffb84d",
          confirmButtonColor: "#ffb84d",
        });
      } else if (txt.includes("expirado")) {
        Swal.fire("Código expirado", "Solicita uno nuevo.", "info");
      } else if (txt.includes("invalido")) {
        Swal.fire("Código inválido", "Verifica e intenta nuevamente.", "error");
      } else if (txt.includes("sin_sesion")) {
        Swal.fire("⚠️ Sesión expirada", "Vuelve a iniciar sesión.", "error")
          .then(() => (window.location.href = "loginOrganizador.php"));
      } else {
        Swal.fire("❌ Error", txt, "error");
      }
    } catch (err) {
      console.error("Error:", err);
      Swal.fire("Error de conexión", "No se pudo contactar con el servidor.", "error");
    }
  });
});