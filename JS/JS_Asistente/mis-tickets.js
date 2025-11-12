console.log("mis-tickets.js cargado correctamente ✅");

document.addEventListener("DOMContentLoaded", () => {
  /* =====================================================
     🦁 LOGO → REDIRIGIR AL INICIO
  ===================================================== */
  const logoInicio = document.getElementById("irInicio");
  if (logoInicio) {
    logoInicio.addEventListener("click", () => {
      window.location.href = "../../index.php?skipVideo=1";
    });
  }

  /* =====================================================
     👤 MENÚ DESPLEGABLE DEL USUARIO
  ===================================================== */
  const userBtn = document.getElementById("userBtn");
  const dropdown = document.getElementById("userDropdown");

  if (userBtn && dropdown) {
    userBtn.addEventListener("click", (e) => {
      e.stopPropagation();
      dropdown.classList.toggle("show");
    });

    window.addEventListener("click", (e) => {
      if (!dropdown.contains(e.target) && !userBtn.contains(e.target)) {
        dropdown.classList.remove("show");
      }
    });
  }

  /* =====================================================
     🎟️ BLOQUEO / DESBLOQUEO DE QR
  ===================================================== */
  const ticketQrs = document.querySelectorAll(".ticket-qr");
  const modal = document.getElementById("modalDesbloqueo");
  const cerrarModal = document.getElementById("cerrarModalDesbloqueo");
  const btnConfirmar = document.getElementById("btnConfirmarDesbloqueo");
  const inputPass = document.getElementById("inputPassword");

  let qrActivo = null; // Guarda el QR seleccionado para desbloquear

  // ✅ Evento: clic sobre el overlay de bloqueo
  ticketQrs.forEach((qr) => {
    const overlay = qr.querySelector(".bloqueo-overlay");

    overlay.addEventListener("click", () => {
      qrActivo = qr;
      modal.style.display = "flex"; // Mostrar modal
      inputPass.value = ""; // Limpiar campo
      inputPass.focus();
    });
  });

  // ✅ Evento: cerrar el modal manualmente
  if (cerrarModal) {
    cerrarModal.addEventListener("click", () => {
      modal.style.display = "none";
    });
  }

  // ✅ Evento: cerrar el modal haciendo clic fuera
  window.addEventListener("click", (e) => {
    if (e.target === modal) modal.style.display = "none";
  });

  // ✅ Evento: confirmar contraseña
  if (btnConfirmar) {
    btnConfirmar.addEventListener("click", () => {
      const password = inputPass.value.trim();

      if (password === "") {
        alert("Por favor ingresa tu contraseña.");
        return;
      }

      // ✅ Validar contraseña contra el backend real
      fetch("../../PHP/PHP_Asistente/verificar_password.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ password }),
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.status === "ok") {
            // ✅ Desbloquear el QR
            qrActivo.classList.add("desbloqueado");
            modal.style.display = "none";
          } else {
            alert("⚠️ " + data.mensaje);
          }
        })
        .catch((err) => {
          console.error("Error:", err);
          alert("Ocurrió un error al verificar la contraseña.");
        });
    });
  }

  /* =====================================================
     🔍 AMPLIAR Y CERRAR QR (modo visualización)
  ===================================================== */
  document.querySelectorAll(".ticket-qr img.qr-imagen").forEach((img) => {
    img.addEventListener("click", (e) => {
      const qr = e.target.closest(".ticket-qr");

      // Solo permite ampliar si está desbloqueado
      if (qr.classList.contains("desbloqueado")) {
        qr.classList.toggle("expandido");

        // Si se expande, bloquea scroll
        document.body.style.overflow = qr.classList.contains("expandido")
          ? "hidden"
          : "auto";
      }
    });
  });
});

/* =====================================================
   🔒 RE-BLOQUEAR QR MANUALMENTE (versión dinámica)
===================================================== */
document.querySelectorAll(".btn-bloquear").forEach((btn) => {
  btn.addEventListener("click", (e) => {
    const ticketCard = e.target.closest(".ticket-card");
    const qr = ticketCard.querySelector(".ticket-qr");

    // Crear notificación tipo “toast”
    const showToast = (msg, tipo = "info") => {
      const toast = document.createElement("div");
      toast.className = `toast ${tipo}`;
      toast.textContent = msg;
      document.body.appendChild(toast);

      // Animar aparición y desaparición
      setTimeout(() => toast.classList.add("show"), 100);
      setTimeout(() => toast.classList.remove("show"), 2500);
      setTimeout(() => toast.remove(), 3200);
    };

    // Animación visual del QR
    if (qr.classList.contains("desbloqueado")) {
      qr.classList.remove("desbloqueado");
      qr.classList.add("bloqueado-anim");
      qr.classList.remove("expandido");
      document.body.style.overflow = "auto";

      // 🔒 Notificación
      showToast("🔒 QR bloqueado nuevamente", "success");

      // efecto visual temporal
      setTimeout(() => qr.classList.remove("bloqueado-anim"), 1000);
    } else {
      showToast("⚠️ Este QR ya está bloqueado", "warning");
    }
  });
});
