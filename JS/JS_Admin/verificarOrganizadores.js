// 🔹 Función auxiliar: validar contraseña de admin
async function validarAdminPassword() {
  const { value: pass } = await Swal.fire({
    title: "🔒 Verificación requerida",
    input: "password",
    inputLabel: "Introduce tu contraseña de administrador",
    inputPlaceholder: "Contraseña",
    showCancelButton: true,
    confirmButtonText: "Verificar",
    cancelButtonText: "Cancelar"
  });

  if (!pass) return false;

  const resp = await fetch("verificarAdminPassword.php", {
    method: "POST",
    body: new URLSearchParams({ password: pass })
  }).then(r => r.json());

  if (resp.status !== "ok") {
    await Swal.fire("Error", resp.mensaje, "error");
    return false;
  }

  return true;
}

// ✅ Aprobar organizador
document.querySelectorAll('.aprobar').forEach(btn => {
  btn.addEventListener('click', async () => {
    const id = btn.dataset.id;

    const confirm = await Swal.fire({
      title: "¿Aprobar organizador?",
      text: "El organizador podrá crear y publicar eventos.",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Sí, aprobar",
      cancelButtonText: "Cancelar"
    });

    if (!confirm.isConfirmed) return;

    const autorizado = await validarAdminPassword();
    if (!autorizado) return;

    const res = await fetch("accionesEvento.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: new URLSearchParams({ accion: "aprobar", id })
    }).then(r => r.json());

    Swal.fire({
      icon: res.status === "ok" ? "success" : "error",
      title: res.mensaje || "Error en la acción",
      showConfirmButton: false,
      timer: 1500
    }).then(() => location.reload());
  });
});

// ✅ Mostrar / Ocultar evento
document.querySelectorAll('.cambiar').forEach(btn => {
  btn.addEventListener('click', async () => {
    const id = btn.dataset.id;
    const accion = btn.dataset.accion;

    const confirm = await Swal.fire({
      title: `¿${accion === 'ocultar' ? 'Ocultar' : 'Mostrar'} evento?`,
      text: accion === 'ocultar'
        ? "El evento dejará de ser visible para los asistentes."
        : "El evento volverá a ser visible públicamente.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Sí, continuar",
      cancelButtonText: "Cancelar"
    });

    if (!confirm.isConfirmed) return;

    const autorizado = await validarAdminPassword();
    if (!autorizado) return;

    const res = await fetch("accionesEvento.php", {
      method: "POST",
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: new URLSearchParams({ accion, id })
    }).then(r => r.json());

    Swal.fire({
      icon: res.status === "ok" ? "success" : "error",
      title: res.mensaje || "Error en la acción",
      showConfirmButton: false,
      timer: 1500
    }).then(() => location.reload());
  });
});
