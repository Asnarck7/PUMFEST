<?php
session_start();
require_once "../conexion.php";

// ✅ Validar sesión
if (!isset($_SESSION['organizador'])) {
    header("Location: loginOrganizador.php");
    exit();
}

$org = $_SESSION['organizador'];
$organizador_id = $org['organizador_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    $fecha_hora = $_POST['fecha_hora'];
    $lugar = trim($_POST['lugar']);
    $ciudad = trim($_POST['ciudad']);
    $categoria = trim($_POST['categoria']);
    $limite = intval($_POST['limiteTickets']);
    $estado = "activo";

    /* ✅ SUBIR IMAGEN */
    $imagenNombre = null;

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
        $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $imagenNombre = uniqid("ev_") . "." . $ext;

        // Crear carpeta si no existe
        if (!is_dir("../../IMG/eventos")) {
            mkdir("../../IMG/eventos", 0777, true);
        }

        move_uploaded_file($_FILES['imagen']['tmp_name'], "../../IMG/eventos/" . $imagenNombre);
    }

    /* ✅ GUARDAR EVENTO */
    $sql = "INSERT INTO eventos (organizador_id, titulo, descripcion, imagen, fecha_hora, lugar, ciudad, categoria, limiteTickets, estado)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "isssssssis",
        $organizador_id,
        $titulo,
        $descripcion,
        $imagenNombre,
        $fecha_hora,
        $lugar,
        $ciudad,
        $categoria,
        $limite,
        $estado
    );

    if ($stmt->execute()) {

        /* ✅ OBTENER ID DEL NUEVO EVENTO */
        $evento_id = $stmt->insert_id;

        /* ✅ GUARDAR CATEGORÍAS DE ENTRADAS */
        if (!empty($_POST['categoria_nombre'])) {

            $totalCupos = 0; // 🔸 Acumulador para sumar cupos totales

            foreach ($_POST['categoria_nombre'] as $i => $nombreCat) {

                /* ✅ LIMPIAR PRECIO FORMATO COP (20.000 → 20000) */
                $precio = str_replace(".", "", $_POST['categoria_precio'][$i]);
                $precio = intval($precio);

                $cupos = intval($_POST['categoria_cupos'][$i]);
                $totalCupos += $cupos; // 🔸 Sumar los cupos totales de todas las categorías

                $sqlCat = "INSERT INTO categorias_entrada (evento_id, nombre, precio, cantidad_disponible)
                           VALUES (?, ?, ?, ?)";

                $stmtCat = $conn->prepare($sqlCat);
                $stmtCat->bind_param("isdi", $evento_id, $nombreCat, $precio, $cupos);
                $stmtCat->execute();
            }

            // ✅ Ajustar el límite total del evento con la suma de categorías (por seguridad)
            $updateLimite = $conn->prepare("UPDATE eventos SET limiteTickets = ? WHERE evento_id = ?");
            $updateLimite->bind_param("ii", $totalCupos, $evento_id);
            $updateLimite->execute();
        }

        header("Location: eventosOrganizador.php?creado=1");
        exit();
    } else {
        $error = "Error al crear evento.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear Evento</title>
    <link rel="stylesheet" href="../../CSS/CSS_Organizador/crearEvento.css">
</head>

<body>

    <div class="contenedor">

        <h1>➕ Crear Nuevo Evento</h1>

        <?php if (isset($error)): ?>
            <p class="error"><?= $error ?></p>
        <?php endif; ?>

        <form id="formCrearEvento" method="POST" enctype="multipart/form-data">

            <label>Título</label>
            <input type="text" name="titulo" required>

            <label>Descripción</label>
            <textarea name="descripcion" rows="5" required></textarea>

            <label>Fecha y hora</label>
            <input type="datetime-local" name="fecha_hora" required>

            <label>Lugar</label>
            <input type="text" name="lugar" required>

            <label>Ciudad</label>
            <input type="text" name="ciudad" required>

            <label for="categoria">Categoría del evento</label>
            <div class="select-wrapper categoria-select">
                <select name="categoria" id="categoria" required>
                    <option value="">Selecciona una categoría</option>
                    <option value="conciertos">Conciertos</option>
                    <option value="teatro">Teatro</option>
                    <option value="deporte">Deporte</option>
                    <option value="familiar">Familiar</option>
                    <option value="festival">Festival</option>
                    <option value="conferencia">Conferencia</option>
                    <option value="tecnologia">Tecnología</option>
                    <option value="gastronomia">Gastronomía</option>
                    <option value="moda">Moda</option>
                </select>
            </div>

            <label>Imagen del evento</label>
            <input type="file" name="imagen" accept="image/*" required>

            <label>Límite de tickets</label>
            <input type="number" name="limiteTickets" min="1" required>

            <h2>Categorías de Entradas</h2>

            <div id="categorias-container">
                <div class="categoria-item">
                    <label>Nombre de la categoría</label>
                    <input type="text" name="categoria_nombre[]" placeholder="Ej: VIP" required>

                    <label>Precio</label>
                    <input type="number" name="categoria_precio[]" min="1" required>

                    <label>Cupos disponibles</label>
                    <input type="number" name="categoria_cupos[]" min="1" required>

                    <button type="button" class="btn-eliminar" onclick="eliminarCategoria(this)">Eliminar</button>
                </div>
            </div>

            <button type="button" class="btn-agregar" onclick="agregarCategoria()">➕ Agregar categoría</button>

            <button type="submit" class="btn-crear">Crear Evento ✅</button>
            <button type="button" class="btn-volver" onclick="location.href='eventosOrganizador.php'">⬅ Volver</button>
        </form>
    </div>

    <script src="../../JS/JS_Organizador/crearEvento.js"></script>

</body>
</html>