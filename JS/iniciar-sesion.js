document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector(".login-form");
  const alerta = document.getElementById("alerta");
  const alertaTexto = document.getElementById("alerta-texto");
  const cerrarAlerta = document.getElementById("cerrarAlerta");
  const loader = document.getElementById("loader");

  // Mostrar alerta con imagen y texto
  function mostrarAlerta(mensaje) {
    alertaTexto.textContent = mensaje;
    alerta.style.display = "flex";
  }

  cerrarAlerta.addEventListener("click", () => {
    alerta.style.display = "none";
  });

  // Enviar formulario con animación de carga
  form.addEventListener("submit", (e) => {
    e.preventDefault(); // Evita el envío tradicional
    loader.style.display = "flex"; // Mostrar loader inmediatamente

    const datos = new FormData(form);

    fetch(form.action, {
      method: "POST",
      body: datos
    })
      .then(res => res.json())
      .then(data => {
        if (data.ok) {
          // Mostrar loader durante 3 segundos antes de redirigir
          setTimeout(() => {
            loader.style.display = "none";
            window.location.href = data.redirect;
            //tiempo de la animacion
          }, 2000);
        } else {
          loader.style.display = "none";
          mostrarAlerta(data.mensaje);
        }
      })
      .catch(() => {
        loader.style.display = "none";
        mostrarAlerta("Error de conexión con el servidor.");
      });
  });
});
