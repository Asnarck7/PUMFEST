console.log("eventosOrganizador.js cargado correctamente ✅");

/* ===========================================================
   🗑 ELIMINAR EVENTO — Verifica contraseña con backend
=========================================================== */
function eliminarEvento(id) {
  Swal.fire({
    title: "🗑 ¿Eliminar evento?",
    html: `<p style="font-size:16px; color:#ddd; margin-top:5px;">
            Por seguridad, ingresa tu contraseña para confirmar:
           </p>`,
    input: "password",
    inputPlaceholder: "Tu contraseña",
    background: "#1e1a24",
    color: "#fff",
    showCancelButton: true,
    confirmButtonText: "Eliminar",
    cancelButtonText: "Cancelar",
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    inputAttributes: {
      autocapitalize: "off",
      autocomplete: "current-password",
    },
    preConfirm: (password) => {
      if (!password) {
        Swal.showValidationMessage("⚠️ Debes ingresar tu contraseña");
      }
      return password;
    },
  }).then((result) => {
    if (result.isConfirmed) {
      // ✅ Verificar contraseña primero
      fetch("verificarPasswordOrganizador.php", {
        method: "POST",
        body: new URLSearchParams({ password: result.value }),
      })
        .then((res) => res.json())
        .then((data) => {
          console.log(data); // Debug en consola

          if (data.status === "ok") {
            // ✅ Si la contraseña es correcta, proceder a eliminar
            fetch("eliminarEvento.php", {
              method: "POST",
              headers: { "Content-Type": "application/x-www-form-urlencoded" },
              body: `id=${encodeURIComponent(id)}`,
            })
              .then((r) => r.text())
              .then((texto) => {
                if (texto.trim() === "ok") {
                  Swal.fire({
                    icon: "success",
                    title: "✅ Evento eliminado",
                    text: "El evento se eliminó correctamente.",
                    background: "#1e1a24",
                    color: "#fff",
                    timer: 1600,
                    showConfirmButton: false,
                  }).then(() => location.reload());
                } else {
                  Swal.fire({
                    icon: "error",
                    title: "❌ Error al eliminar",
                    text: texto,
                    background: "#1e1a24",
                    color: "#fff",
                  });
                }
              });
          } else {
            Swal.fire({
              icon: "error",
              title: "Contraseña incorrecta ❌",
              text: "Inténtalo nuevamente.",
              background: "#1e1a24",
              color: "#fff",
            });
          }
        })
        .catch(() => {
          Swal.fire({
            icon: "error",
            title: "Error de conexión ⚠️",
            text: "No se pudo conectar con el servidor.",
            background: "#1e1a24",
            color: "#fff",
          });
        });
    }
  });
}

/* ===========================================================
   ✏ EDITAR EVENTO — Pide contraseña antes de continuar
=========================================================== */
function editarEvento(id) {
  Swal.fire({
    title: "✏️ ¿Editar evento?",
    html: `<p style="font-size:16px; color:#ddd; margin-top:5px;">
            Confirma tu contraseña para continuar con la edición.
           </p>`,
    input: "password",
    inputPlaceholder: "Tu contraseña",
    background: "#1e1a24",
    color: "#fff",
    showCancelButton: true,
    confirmButtonText: "Verificar",
    cancelButtonText: "Cancelar",
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#aaa",
    inputAttributes: {
      autocapitalize: "off",
      autocomplete: "current-password",
    },
    preConfirm: (password) => {
      if (!password) {
        Swal.showValidationMessage("⚠️ Debes ingresar tu contraseña");
      }
      return fetch("verificarPasswordOrganizador.php", {
        method: "POST",
        body: new URLSearchParams({ password }),
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.status !== "ok") {
            throw new Error(data.mensaje);
          }
          return true;
        })
        .catch((err) => {
          Swal.showValidationMessage(`❌ ${err.message}`);
        });
    },
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire({
        icon: "success",
        title: "🔓 Contraseña verificada",
        text: "Accediendo al modo de edición...",
        background: "#1e1a24",
        color: "#fff",
        showConfirmButton: false,
        timer: 1200,
      }).then(() => {
        location.href = "editarEvento.php?id=" + id;
      });
    }
  });
}
