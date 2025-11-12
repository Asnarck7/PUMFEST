document.addEventListener("DOMContentLoaded", () => {
  const claveInput = document.getElementById("claveAdmin");
  const btnEntrar = document.getElementById("btnEntrar");
  const mensaje = document.getElementById("mensaje");
  const loader = document.getElementById("loader");

  function mostrarMensaje(texto, tipo = "info") {
    mensaje.textContent = texto;
    mensaje.style.color = tipo === "error" ? "red" : "#00ff88";
  }

  btnEntrar.addEventListener("click", async () => {
    const clave = claveInput.value.trim();
    if (!clave) return mostrarMensaje("⚠️ Ingresa la clave.", "error");

    loader.style.display = "block";
    mensaje.textContent = "";

    try {
      const res = await fetch("adminLogin.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `clave=${encodeURIComponent(clave)}`
      });

      const text = await res.text();
      let data;
      try {
        data = JSON.parse(text);
      } catch {
        console.error("Respuesta inesperada del servidor:", text);
        throw new Error("Respuesta no válida del servidor");
      }

      loader.style.display = "none";

      if (data.status === "ok") {
        mostrarMensaje("✅ Acceso autorizado, redirigiendo...");
        setTimeout(() => (window.location.href = "iniciarSesionAdmin.php"), 1000);
      } else {
        mostrarMensaje(data.mensaje, "error");
      }
    } catch (error) {
      loader.style.display = "none";
      mostrarMensaje("Error al conectar con el servidor.", "error");
      console.error(error);
    }
  });
});
