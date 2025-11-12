console.log("crearEvento.js cargado correctamente ✅");

// ✅ Esperar a que todo el DOM esté listo antes de acceder al formulario
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("formCrearEvento");
    if (!form) {
        console.error("❌ No se encontró el formulario #formCrearEvento");
        return;
    }

    // ✅ Validación simple de envío
    form.addEventListener("submit", e => {
        console.log("Formulario enviado ✅");
    });

    // 🎟️ Actualizar cupos automáticamente
    const container = document.getElementById("categorias-container");
    const limiteInput = document.querySelector('input[name="limiteTickets"]');
    const btnCrear = document.querySelector(".btn-crear");

    let msg = document.createElement("p");
    msg.id = "cupos-msg";
    msg.style.marginTop = "10px";
    msg.style.fontWeight = "bold"; 
    container.parentElement.insertBefore(msg, container.nextSibling);

    const barraContainer = document.createElement("div");
    barraContainer.id = "barra-container";
    barraContainer.style.height = "10px";
    barraContainer.style.width = "100%";
    barraContainer.style.borderRadius = "6px";
    barraContainer.style.marginTop = "5px";
    barraContainer.style.background = "#ddd";
    container.parentElement.insertBefore(barraContainer, msg.nextSibling);

    const barra = document.createElement("div");
    barra.id = "barra-progreso";
    barra.style.height = "100%";
    barra.style.width = "0%";
    barra.style.transition = "width 0.4s ease";
    barra.style.borderRadius = "6px";
    barra.style.background = "#00c853";
    barraContainer.appendChild(barra);

    // ✅ Función para actualizar progreso
    window.actualizarCupos = function () {
        const limite = parseInt(limiteInput?.value) || 0;
        let total = 0;

        document.querySelectorAll('input[name="categoria_cupos[]"]').forEach((input) => {
            total += parseInt(input.value) || 0;
        });

        if (limite === 0) {
            msg.textContent = "";
            barra.style.width = "0%";
            btnCrear.disabled = false;
            return;
        }

        const porcentaje = Math.min((total / limite) * 100, 100);
        barra.style.width = `${porcentaje}%`;

        if (total > limite) {
            msg.textContent = `⚠️ Has superado el límite de tickets (${total}/${limite}).`;
            msg.style.color = "#ff4d4d";
            barra.style.background = "#ff4d4d";
            btnCrear.disabled = true;
        } else {
            msg.textContent = `🎟️ Cupos asignados: ${total}/${limite}`;
            msg.style.color = "#00c853";
            barra.style.background = "#00c853";
            btnCrear.disabled = false;
        }
    };

    if (limiteInput) limiteInput.addEventListener("input", actualizarCupos);
    container.addEventListener("input", actualizarCupos);
});

// ==========================================================
// 🧩 Función: Formatear precio COP
// ==========================================================
function formatearCOP(input) {
    let valor = input.value.replace(/\D/g, "");
    if (valor === "") {
        input.value = "";
        return;
    }
    let numero = parseInt(valor);
    input.value = numero.toLocaleString("es-CO");
}

// ==========================================================
// 🧩 Agregar categoría dinámica
// ==========================================================
function agregarCategoria() {
    const container = document.getElementById("categorias-container");
    if (!container) return;

    const div = document.createElement("div");
    div.className = "categoria-item animar";

    div.innerHTML = `
        <label>Nombre de la categoría</label>
        <input type="text" name="categoria_nombre[]" placeholder="Ej: VIP" required>

        <label>Precio</label>
        <input type="text" name="categoria_precio[]" oninput="formatearCOP(this)" required>

        <label>Cupos disponibles</label>
        <input type="number" name="categoria_cupos[]" min="1" required>

        <button type="button" class="btn-eliminar" onclick="eliminarCategoria(this)">Eliminar</button>
    `;

    container.appendChild(div);
    if (window.actualizarCupos) actualizarCupos();
}

// ==========================================================
// 🧩 Eliminar categoría con animación
// ==========================================================
function eliminarCategoria(btn) {
    btn.parentElement.classList.add("eliminarAnim");
    setTimeout(() => {
        btn.parentElement.remove();
        if (window.actualizarCupos) actualizarCupos();
    }, 200);
}
