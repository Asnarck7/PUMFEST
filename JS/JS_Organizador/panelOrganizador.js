console.log("Panel del organizador cargado correctamente");

// Referencia al botón
const logoutBtn = document.getElementById("logoutBtn");

logoutBtn.addEventListener("click", () => {
  Swal.fire({
    title: "¿Cerrar sesión?",
    text: "Tu sesión se cerrará y volverás al login.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#c54a2b",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Sí, cerrar sesión",
    cancelButtonText: "Cancelar"
  }).then((result) => {
    if (result.isConfirmed) {
      fetch("logoutOrganizador.php", { method: "GET" })
        .then((res) => res.json())
        .then((data) => {
          Swal.fire({
            icon: data.ok ? "success" : "error",
            title: data.ok ? "Sesión cerrada" : "Error",
            text: data.mensaje || "Tu sesión se ha cerrado correctamente.",
            confirmButtonColor: "#c54a2b"
          }).then(() => {
            window.location.href = "loginOrganizador.php";
          });
        })
        .catch(() => {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "No se pudo cerrar la sesión. Intenta nuevamente.",
          });
        });
    }
  });
});
