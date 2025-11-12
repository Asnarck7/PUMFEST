/**
 * panelAdmin.js
 * Control principal del panel de administración de PUMFEST
 * Maneja navegación, acciones críticas y confirmaciones de seguridad.
 */

document.addEventListener("DOMContentLoaded", () => {
  //boton banner
  const btnBanners = document.querySelector(".btn-banners");
  if (btnBanners) {
    btnBanners.addEventListener("click", () => {
      window.location.href = "BannersAdmin.php";
    });
  }
  //boton para eventos destacados ⭐
  const btnDestacados = document.querySelector(".btn-destacados");
  if (btnDestacados) {
    btnDestacados.addEventListener("click", () => {
      window.location.href = "DestacadosAdmin.php";
    });
  }

  // 👥 Ver asistentes (con verificación de contraseña)
  const btnAsistentes = document.querySelector(".btn-asistentes");
  if (btnAsistentes) {
    btnAsistentes.addEventListener("click", async () => {
      const { value: pass } = await Swal.fire({
        title: "🔒 Acceso restringido",
        input: "password",
        inputLabel: "Introduce tu contraseña de administrador",
        inputPlaceholder: "Contraseña",
        showCancelButton: true,
        confirmButtonText: "Verificar",
        cancelButtonText: "Cancelar",
        inputAttributes: { autocapitalize: "off" },
      });

      if (!pass) return;

      const resp = await fetch("verificarAdminPassword.php", {
        method: "POST",
        body: new URLSearchParams({ password: pass }),
      }).then((r) => r.json());

      if (resp.status === "ok") {
        await Swal.fire({
          icon: "success",
          title: "✅ Acceso concedido",
          text: "Redirigiendo al listado de asistentes...",
          timer: 1500,
          showConfirmButton: false,
        });
        window.location.href = "verAsistentes.php";
      } else {
        Swal.fire({
          icon: "error",
          title: "❌ Error de autenticación",
          text: resp.mensaje,
        });
      }
    });
  }

  console.log("✅ Panel Admin cargado correctamente");

  const btnVerificar = document.querySelector(".btn-verificar");
  const btnLista = document.querySelector(".btn-lista");
  const btnLogout = document.querySelector(".logout-btn");

  // 🚪 Cerrar sesión con confirmación
  if (btnLogout) {
    btnLogout.addEventListener("click", async () => {
      const confirm = await Swal.fire({
        title: "¿Cerrar sesión?",
        text: "Tu sesión de administrador se cerrará.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#ef4444",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Sí, salir",
        cancelButtonText: "Cancelar",
      });

      if (confirm.isConfirmed) {
        window.location.href = "logoutAdmin.php";
      }
    });
  }

  // ✅ Ir a verificar organizadores
  if (btnVerificar) {
    btnVerificar.addEventListener("click", () => {
      window.location.href = "verificarOrganizadores.php";
    });
  }

  // 📋 Ir a lista de verificados
  if (btnLista) {
    btnLista.addEventListener("click", () => {
      window.location.href = "verOrganizadores.php";
    });
  }

  // ⚡ Escucha acciones dinámicas (aprobar, rechazar, eliminar)
  document.body.addEventListener("click", async (e) => {
    if (e.target.matches(".btn-accion")) {
      const accion = e.target.dataset.accion;
      const id = e.target.dataset.id;

      if (!accion || !id) return;

      const confirm = await Swal.fire({
        title: `¿Confirmar acción: ${accion}?`,
        text: "Esta acción no se puede deshacer.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#16a34a",
        cancelButtonColor: "#ef4444",
        confirmButtonText: "Sí, continuar",
        cancelButtonText: "Cancelar",
      });

      if (confirm.isConfirmed) {
        // 🔐 Confirmar contraseña del admin antes de continuar
        const { value: password } = await Swal.fire({
          title: "Verificación adicional",
          text: "Por seguridad, ingresa tu contraseña:",
          input: "password",
          inputPlaceholder: "Contraseña del administrador",
          showCancelButton: true,
          confirmButtonText: "Verificar",
        });

        if (!password) return;

        // 🧠 Verificar contraseña en el servidor
        const passCheck = await fetch("verificarAdminPassword.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: `password=${encodeURIComponent(password)}`,
        });

        const passData = await passCheck.json();

        if (passData.status !== "ok") {
          Swal.fire("❌ Error", passData.mensaje, "error");
          return;
        }

        // 🧾 Si la contraseña fue válida, ejecutar la acción principal
        try {
          const res = await fetch("accionesEvento.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `accion=${encodeURIComponent(accion)}&id=${encodeURIComponent(
              id
            )}`,
          });

          const data = await res.json();

          if (data.status === "ok") {
            Swal.fire("✅ Éxito", data.mensaje, "success").then(() =>
              location.reload()
            );
          } else {
            Swal.fire("⚠️ Error", data.mensaje, "error");
          }
        } catch (error) {
          Swal.fire(
            "⚠️ Error",
            "No se pudo conectar con el servidor.",
            "error"
          );
          console.error(error);
        }
      }
    }
  });
});

document.addEventListener("DOMContentLoaded", () => {
  console.log("✅ Panel Admin cargado correctamente");

  // 🟢 Botón para verificar organizadores
  document.querySelector(".btn-verificar").addEventListener("click", () => {
    window.location.href = "verificarOrganizadores.php";
  });

  // 📋 Botón para lista de verificados
  document.querySelector(".btn-lista").addEventListener("click", () => {
    window.location.href = "verOrganizadores.php";
  });

  // 🚨 Solicitudes de organizadores (con verificación de contraseña)
  document
    .querySelector(".btn-solicitudes")
    .addEventListener("click", async () => {
      const { value: pass } = await Swal.fire({
        title: "🔒 Verificación requerida",
        input: "password",
        inputLabel: "Introduce tu contraseña de administrador",
        inputPlaceholder: "Contraseña",
        showCancelButton: true,
        confirmButtonText: "Verificar",
        cancelButtonText: "Cancelar",
        inputAttributes: { autocapitalize: "off" },
      });

      if (!pass) return;

      const resp = await fetch("verificarAdminPassword.php", {
        method: "POST",
        body: new URLSearchParams({ password: pass }),
      }).then((r) => r.json());

      if (resp.status === "ok") {
        await Swal.fire({
          icon: "success",
          title: "Acceso concedido",
          text: "Redirigiendo a solicitudes...",
          timer: 1500,
          showConfirmButton: false,
        });
        window.location.href = "solicitudesOrganizadores.php";
      } else {
        Swal.fire({
          icon: "error",
          title: "Error de autenticación",
          text: resp.mensaje,
          confirmButtonColor: "#e53935",
        });
      }
    });

  // 🚪 Cerrar sesión
  document.querySelector(".logout-btn").addEventListener("click", async () => {
    const confirm = await Swal.fire({
      title: "¿Cerrar sesión?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Sí, salir",
      cancelButtonText: "Cancelar",
    });

    if (confirm.isConfirmed) {
      window.location.href = "../../PHP/PHP_Admin/logoutAdmin.php";
    }
  });
});
