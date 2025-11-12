<?php
session_start();
require_once "../conexion.php";

// ✅ Mostrar errores (por si los hay)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// ✅ Validar sesión
if (!isset($_SESSION['organizador'])) {
    header("Location: loginOrganizador.php");
    exit();
}

$org = $_SESSION['organizador'];
$organizador_id = $org['organizador_id'];

// ✅ Validar ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("<h2>Error: ID de evento inválido.</h2>");
}

$evento_id = intval($_GET['id']);

// ✅ Obtener información del evento
$sql = "SELECT * FROM eventos WHERE evento_id = ? AND organizador_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $evento_id, $organizador_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("<h2>Error: No tienes permiso para editar este evento.</h2>");
}

$evento = $result->fetch_assoc();

/* ✅ VERIFICAR FECHA DE CREACIÓN (2 días máximo) */
$fecha_creacion = strtotime($evento["fecha_hora"]);
$diferencia = time() - $fecha_creacion;
$dos_dias = 2 * 24 * 60 * 60;

$permitir_editar = ($diferencia <= $dos_dias);

// ✅ Si envían formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $permitir_editar) {

    $titulo = trim($_POST['titulo']);
    $descripcion = trim($_POST['descripcion']);
    $fecha_hora = $_POST['fecha_hora'];
    $lugar = trim($_POST['lugar']);
    $ciudad = trim($_POST['ciudad']);
    $categoria = trim($_POST['categoria']);
    $limite = intval($_POST['limiteTickets']);

    // ✅ Imagen
    $imagenActual = $evento['imagen'];
    $imagenNueva = $imagenActual;

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
        $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
        $imagenNueva = uniqid("ev_") . "." . $ext;

        if (!is_dir("../../IMG/eventos")) {
            mkdir("../../IMG/eventos", 0777, true);
        }

        move_uploaded_file($_FILES['imagen']['tmp_name'], "../../IMG/eventos/" . $imagenNueva);
    }

    // ✅ Actualizar evento
    $sqlUpdate = "UPDATE eventos SET titulo=?, descripcion=?, imagen=?, fecha_hora=?, lugar=?, ciudad=?, categoria=?, limiteTickets=? 
                  WHERE evento_id=? AND organizador_id=?";

    $stmtUp = $conn->prepare($sqlUpdate);
    $stmtUp->bind_param(
        "sssssssiii",
        $titulo,
        $descripcion,
        $imagenNueva,
        $fecha_hora,
        $lugar,
        $ciudad,
        $categoria,
        $limite,
        $evento_id,
        $organizador_id
    );

    if ($stmtUp->execute()) {

        // ✅ Actualizar categorías
        $conn->query("DELETE FROM categorias_entrada WHERE evento_id = $evento_id");

        if (!empty($_POST['categoria_nombre'])) {
            foreach ($_POST['categoria_nombre'] as $i => $nombreCat) {

                $precio = $_POST['categoria_precio'][$i];
                $cupos = $_POST['categoria_cupos'][$i];

                $sqlCat = "INSERT INTO categorias_entrada (evento_id, nombre, precio, cantidad_disponible)
                           VALUES (?, ?, ?, ?)";

                $stmtCat = $conn->prepare($sqlCat);
                $stmtCat->bind_param("isdi", $evento_id, $nombreCat, $precio, $cupos);
                $stmtCat->execute();
            }
        }

        header("Location: eventosOrganizador.php?editado=1");
        exit();
    }
}

// ✅ Obtener categorías existentes
$catSQL = "SELECT * FROM categorias_entrada WHERE evento_id = ?";
$stmtCat = $conn->prepare($catSQL);
$stmtCat->bind_param("i", $evento_id);
$stmtCat->execute();
$categorias = $stmtCat->get_result();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Evento</title>
    <link rel="stylesheet" href="../../CSS/CSS_Organizador/crearEvento.css">
</head>

<body>

<div class="contenedor">

    <h1>✏ Editar Evento</h1>

    <?php if (!$permitir_editar): ?>
        <p class="error">⛔ Este evento tiene más de 2 días de creado. Ya no se puede editar.</p>
        <button onclick="location.href='eventosOrganizador.php'">⬅ Volver</button>
        </div>
    </body>
</html>
<?php exit; endif; ?>

<form method="POST" enctype="multipart/form-data">

    <label>Título</label>
    <input type="text" name="titulo" value="<?= htmlspecialchars($evento['titulo']) ?>" required>

    <label>Descripción</label>
    <textarea name="descripcion" rows="5" required><?= htmlspecialchars($evento['descripcion']) ?></textarea>

    <label>Fecha y hora</label>
    <input type="datetime-local" name="fecha_hora" value="<?= date('Y-m-d\TH:i', strtotime($evento['fecha_hora'])) ?>" required>

    <label>Lugar</label>
    <input type="text" name="lugar" value="<?= htmlspecialchars($evento['lugar']) ?>" required>

    <label>Ciudad</label>
    <input type="text" name="ciudad" value="<?= htmlspecialchars($evento['ciudad']) ?>" required>

    <div class="categoria-select">
    <label for="categoria">Categoría del evento</label>
    <div class="select-wrapper">
        <select name="categoria" id="categoria" required>
            <option <?= $evento['categoria']=="conciertos"?"selected":"" ?>>Conciertos</option>
            <option <?= $evento['categoria']=="teatro"?"selected":"" ?>>Teatro</option>
            <option <?= $evento['categoria']=="deporte"?"selected":"" ?>>Deporte</option>
            <option <?= $evento['categoria']=="familiar"?"selected":"" ?>>Familiar</option>
            <option <?= $evento['categoria']=="festival"?"selected":"" ?>>Festival</option>
            <option <?= $evento['categoria']=="conferencia"?"selected":"" ?>>Conferencia</option>
            <option <?= $evento['categoria']=="tecnología"?"selected":"" ?>>Tecnología</option>
            <option <?= $evento['categoria']=="gastronomía"?"selected":"" ?>>Gastronomía</option>
            <option <?= $evento['categoria']=="moda"?"selected":"" ?>>Moda</option>
        </select>
        <span class="arrow">▼</span>
    </div>
</div>


    <label>Imagen actual</label>
    <img src="../../IMG/eventos/<?= $evento['imagen'] ?>" width="200" style="border-radius:10px;">

    <label>Subir nueva imagen (opcional)</label>
    <input type="file" name="imagen" accept="image/*">

    <label>Límite de tickets</label>
    <input type="number" name="limiteTickets" value="<?= $evento['limiteTickets'] ?>" min="1" required>

    <h2>Categorías de Entradas</h2>

    <div id="categorias-container">

        <?php while ($cat = $categorias->fetch_assoc()): ?>
            <div class="categoria-item">
                <label>Nombre</label>
                <input type="text" name="categoria_nombre[]" value="<?= htmlspecialchars($cat['nombre']) ?>" required>

                <label>Precio</label>
                <input type="number" name="categoria_precio[]" value="<?= $cat['precio'] ?>" required>

                <label>Cupos</label>
                <input type="number" name="categoria_cupos[]" value="<?= $cat['cantidad_disponible'] ?>" required>

                <button type="button" class="btn-eliminar" onclick="eliminarCategoria(this)">Eliminar</button>
            </div>
        <?php endwhile; ?>
    </div>

    <button type="button" class="btn-agregar" onclick="agregarCategoria()">➕ Agregar categoría</button>

    <button type="submit" class="btn-crear">💾 Guardar Cambios</button>
    <button type="button" class="btn-volver" onclick="location.href='eventosOrganizador.php'">⬅ Volver</button>

</form>

</div>

<script src="../../JS/JS_Organizador/crearEvento.js"></script>

</body>
</html>
