<?php
session_start();
require_once "../conexion.php";

// ==============================
// 🔐 Validar sesión de organizador
// ==============================
if (!isset($_SESSION['organizador'])) {
    header("Location: loginOrganizador.php");
    exit();
}

$org = $_SESSION['organizador'];

// Intentar obtener el ID del organizador
$orgId = isset($org['organizador_id']) ? (int) $org['organizador_id'] : null;

if (!$orgId && isset($org['usuario_id'])) {
    $usuarioId = (int) $org['usuario_id'];
    $stmt = $conn->prepare("SELECT organizador_id FROM organizadores WHERE usuario_id = ? LIMIT 1");
    $stmt->bind_param("i", $usuarioId);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res && $res->num_rows > 0) {
        $r = $res->fetch_assoc();
        $orgId = (int) $r['organizador_id'];
        $_SESSION['organizador']['organizador_id'] = $orgId;
    }
}

if (!$orgId) {
    unset($_SESSION['organizador']);
    header("Location: loginOrganizador.php?error=session_incomplete");
    exit();
}

// ==============================
// 📦 Obtener eventos con categorías y tickets
// ==============================
$sql = "
SELECT 
    e.evento_id,
    e.titulo,
    e.fecha_hora,
    e.lugar,
    e.estado,
    e.limiteTickets,
    c.categoria_id,
    c.nombre AS categoria_nombre,
    c.cantidad_disponible,
    c.precio
FROM eventos e
LEFT JOIN categorias_entrada c ON e.evento_id = c.evento_id
WHERE e.organizador_id = ?
ORDER BY e.fecha_hora ASC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $orgId);
$stmt->execute();
$result = $stmt->get_result();

// Agrupar los datos por evento
$eventos = [];
while ($row = $result->fetch_assoc()) {
    $id = $row['evento_id'];
    if (!isset($eventos[$id])) {
        $eventos[$id] = [
            'titulo' => $row['titulo'],
            'fecha_hora' => $row['fecha_hora'],
            'lugar' => $row['lugar'],
            'estado' => $row['estado'],
            'limiteTickets' => $row['limiteTickets'],
            'categorias' => []
        ];
    }

    // Si el evento tiene categorías asociadas, agrégalas
    if ($row['categoria_id']) {
        $eventos[$id]['categorias'][] = [
            'nombre' => $row['categoria_nombre'],
            'disponibles' => $row['cantidad_disponible'],
            'precio' => $row['precio']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>🎵 Mis Eventos</title>
    <link rel="stylesheet" href="../../CSS/CSS_Organizador/eventosOrganizador.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="contenedor">
        <h1>🎵 Mis Eventos</h1>
        <button class="btn btn-crear" onclick="location.href='crearEvento.php'">➕ Crear Evento</button>

        <?php if (!empty($eventos)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Fecha</th>
                        <th>Lugar</th>
                        <th>Estado</th>
                        <th>🎟️ Límite</th>
                        <th>Categorías / Disponibles</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($eventos as $id => $ev): ?>
                        <tr>
                            <td><?= htmlspecialchars($ev['titulo']) ?></td>
                            <td><?= htmlspecialchars($ev['fecha_hora']) ?></td>
                            <td><?= htmlspecialchars($ev['lugar']) ?></td>
                            <td><?= htmlspecialchars($ev['estado']) ?></td>
                            <td><?= (int) $ev['limiteTickets'] ?></td>

                            <!-- 🔽 Columna con categorías, precios, disponibles y barra -->
                            <td>
                                <?php if (!empty($ev['categorias'])): ?>
                                    <ul class="categorias-lista">
                                        <?php foreach ($ev['categorias'] as $cat): ?>
                                            <?php
                                            $vendidos = max(0, $ev['limiteTickets'] - $cat['disponibles']);
                                            $porcentaje = $ev['limiteTickets'] > 0
                                                ? round(($vendidos / $ev['limiteTickets']) * 100)
                                                : 0;

                                            // 🎨 Color dinámico según porcentaje
                                            if ($porcentaje < 50)
                                                $color = "#2ecc71";       // Verde (baja ocupación)
                                            elseif ($porcentaje < 80)
                                                $color = "#f1c40f";  // Amarillo (media)
                                            else
                                                $color = "#e74c3c";                        // Rojo (casi lleno)
                                            ?>
                                            <li class="categoria-item">
                                                <strong><?= htmlspecialchars($cat['nombre']) ?></strong>
                                                — <?= (int) $cat['disponibles'] ?> disponibles
                                                <span class="precio">($<?= number_format($cat['precio'], 2) ?>)</span>
                                                <div class="barra-container">
                                                    <div class="barra-progreso"
                                                        style="width: <?= $porcentaje ?>%; background-color: <?= $color ?>;">
                                                    </div>
                                                </div>
                                                <small><?= $porcentaje ?>% vendidos</small>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <em>Sin categorías registradas</em>
                                <?php endif; ?>
                            </td>

                            <!-- 🧭 Acciones -->
                            <td class="acciones">
                                <button class="btn btn-editar" onclick="editarEvento(<?= $id ?>)">
                                    ✏ Editar
                                </button>
                                <button class="btn btn-eliminar" onclick="eliminarEvento(<?= $id ?>)">
                                    🗑 Eliminar
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="sin-eventos">Aún no tienes eventos creados.</p>
        <?php endif; ?>

        <button class="btn btn-volver" onclick="location.href='panelOrganizador.php'">⬅ Volver</button>
    </div>

    <script src="../../JS/JS_Organizador/eventosOrganizador.js"></script>
</body>

</html>