// Para el video on el id promvideo al darle click sobre el logo permitira ver
// una pequeña animacion del video que permita 
document.addEventListener("DOMContentLoaded", () => {
  const logo = document.querySelector(".logo");
  const overlay = document.getElementById("videoOverlay");
  const video = document.getElementById("promoVideo");

  if (logo && overlay && video) {
    // Evita que el video quede reproduciéndose al volver atrás
    video.pause();
    video.currentTime = 0;
    video.muted = true;

    // Reproduce solo al hacer click en el logo
    logo.addEventListener("click", () => {
      overlay.classList.add("show");
      video.muted = false;
       // volumen
      video.volume = 0.25;
       // empieza desde el inicio
      video.currentTime = 0;

      video.play().catch((err) => {
        console.warn("El navegador bloqueó la reproducción automática:", err);
      });

      // Cierra automáticamente 
      setTimeout(() => {
        overlay.classList.remove("show");
        video.pause();
        video.currentTime = 0;
      }, 3000);
    });

    // Si el asistente navega fuera o vuelve, asegúrate de que se detenga ya que 
    // era algo incomodo al volver al index reproducia el video haora creamos un una regla
    window.addEventListener("pagehide", () => {
      video.pause();
      video.currentTime = 0;
    });
    window.addEventListener("visibilitychange", () => {
      if (document.hidden) {
        video.pause();
        video.currentTime = 0;
      }
    });
  } else {
    console.warn("⚠️ No se encontró el logo o el video en el DOM.");
  }
});
