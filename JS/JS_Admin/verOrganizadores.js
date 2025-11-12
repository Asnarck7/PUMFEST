// 🔹 Función principal para manejar revocación de organizadores
document.querySelectorAll('.rechazar').forEach(btn => {
  btn.addEventListener('click', async () => {
    const id = btn.dataset.id;

    // 🔒 Solicitar contraseña del admin
    const { value: pass } = await Swal.fire({
      title: "🔐 Verificación requerida",
      input: "password",
      inputLabel: "Introduce tu contraseña de administrador",
      inputPlaceholder: "Contraseña",
      confirmButtonText: "Verificar",
      confirmButtonColor: "#3085d6",
      showCancelButton: true,
      cancelButtonText: "Cancelar",
      inputAttributes: { autocapitalize: "off" },
    });

    if (!pass) return;

    // ✅ Verificar la contraseña
    const resp = await fetch("verificarAdminPassword.php", {
      method: "POST",
      body: new URLSearchParams({ password: pass })
    }).then(r => r.json());

    if (resp.status === "ok") {
      // Mostrar loading mientras se procesa
      Swal.fire({
        title: "Procesando...",
        text: "Revocando organizador y ocultando eventos...",
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading()
      });

      // ✅ Ejecutar revocación
      const accion = await fetch("revocarOrganizador.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id })
      }).then(r => r.json());

      // Esperar un poco para suavizar animación
      await new Promise(r => setTimeout(r, 500));

      // ✅ Mostrar resultado visual
      Swal.fire({
        icon: accion.status === "ok" ? "success" : "error",
        title: accion.mensaje,
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true
      }).then(() => location.reload());
    } else {
      Swal.fire({
        icon: "error",
        title: "❌ Error",
        text: resp.mensaje,
        confirmButtonText: "Entendido"
      });
    }
  });
});
