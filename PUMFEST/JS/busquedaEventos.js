// ======================
// 🔎 BÚSQUEDA DE EVENTOS CON ANIMACIÓN
// ======================
document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.getElementById("searchInput");
  const searchButton = document.getElementById("searchButton");

  const normalizeText = (text) =>
    text
      .toLowerCase()
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, ""); // elimina acentos

  // Mostrar mensaje flotante
  const showMessage = (msg) => {
    let existing = document.querySelector(".search-message");
    if (existing) existing.remove();

    const message = document.createElement("div");
    message.className = "search-message";
    message.textContent = msg;
    document.body.appendChild(message);

    setTimeout(() => message.classList.add("show"), 10);
    setTimeout(() => {
      message.classList.remove("show");
      setTimeout(() => message.remove(), 500);
    }, 3000);
  };

  // Función para buscar evento
  const searchEvent = () => {
    const query = normalizeText(searchInput.value.trim());
    if (!query) {
      showMessage("Por favor escribe algo para buscar.");
      return;
    }

    const events = document.querySelectorAll(".event-info h3");
    let foundCard = null;

    for (const event of events) {
      const eventText = normalizeText(event.textContent);
      if (eventText.includes(query) || query.includes(eventText)) {
        foundCard = event.closest(".event-card"); // Busca el contenedor
        break;
      }
    }

    if (foundCard) {
      foundCard.scrollIntoView({ behavior: "smooth", block: "center" });
      foundCard.classList.add("highlight-event");

      setTimeout(() => {
        foundCard.classList.remove("highlight-event");
      }, 1500);
    } else {
      showMessage(`No se encontró un evento llamado "${searchInput.value}".`);
    }

    searchInput.value = "";
  };

  searchButton.addEventListener("click", searchEvent);
  searchInput.addEventListener("keypress", (e) => {
    if (e.key === "Enter") searchEvent();
  });
});
