-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-11-2025 a las 10:02:35
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `pumfest_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `admin_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `permisos` varchar(100) DEFAULT 'básico'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`admin_id`, `usuario_id`, `permisos`) VALUES
(1, 36, 'superadmin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistentes`
--

CREATE TABLE `asistentes` (
  `asistente_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `preferencias` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asistentes`
--

INSERT INTO `asistentes` (`asistente_id`, `usuario_id`, `preferencias`) VALUES
(42, 42, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carritos`
--

CREATE TABLE `carritos` (
  `carrito_id` int(11) NOT NULL,
  `asistente_id` int(11) NOT NULL,
  `total` decimal(10,2) DEFAULT 0.00,
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrito_items`
--

CREATE TABLE `carrito_items` (
  `item_id` int(11) NOT NULL,
  `carrito_id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT 1,
  `precio_unitario` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias_entrada`
--

CREATE TABLE `categorias_entrada` (
  `categoria_id` int(11) NOT NULL,
  `evento_id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `cantidad_disponible` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias_entrada`
--

INSERT INTO `categorias_entrada` (`categoria_id`, `evento_id`, `nombre`, `precio`, `cantidad_disponible`) VALUES
(60, 9, 'VIP', 100.00, 2),
(61, 9, 'comun', 50.00, 11);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `codigos_verificacion`
--

CREATE TABLE `codigos_verificacion` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `codigo` varchar(10) NOT NULL,
  `tipo` varchar(50) NOT NULL DEFAULT 'general',
  `expira_en` datetime NOT NULL,
  `usado` tinyint(1) DEFAULT 0,
  `creado_en` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `codigos_verificacion`
--

INSERT INTO `codigos_verificacion` (`id`, `usuario_id`, `codigo`, `tipo`, `expira_en`, `usado`, `creado_en`) VALUES
(134, 42, '22343', 'general', '2025-11-12 01:06:00', 1, '2025-11-11 19:04:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entradas`
--

CREATE TABLE `entradas` (
  `entrada_id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `estado` varchar(50) DEFAULT 'disponible'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventos`
--

CREATE TABLE `eventos` (
  `evento_id` int(11) NOT NULL,
  `organizador_id` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `fecha_hora` datetime NOT NULL,
  `lugar` varchar(150) DEFAULT NULL,
  `ciudad` varchar(100) DEFAULT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `limiteTickets` int(11) DEFAULT NULL,
  `estado` varchar(50) DEFAULT 'activo',
  `visible` tinyint(1) DEFAULT 1,
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `destacado` tinyint(1) DEFAULT 0,
  `es_destacado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `eventos`
--

INSERT INTO `eventos` (`evento_id`, `organizador_id`, `titulo`, `descripcion`, `imagen`, `fecha_hora`, `lugar`, `ciudad`, `categoria`, `limiteTickets`, `estado`, `visible`, `fecha_creacion`, `destacado`, `es_destacado`) VALUES
(9, 8, 'KAROL G | MAÑANA SERÁ BONITO TOUR FRESH', 'karol', 'ev_6912fa273a5b1.jpg', '2025-11-10 18:00:00', 'Cr 5 Calle 37 Estadio Manuel Murillo toro 2', 'Ibagué', 'Conciertos', 13, 'activo', 0, '2025-11-08 23:45:25', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `organizadores`
--

CREATE TABLE `organizadores` (
  `organizador_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `biografia` text DEFAULT NULL,
  `verificado` tinyint(1) DEFAULT 0,
  `verificado_por_admin` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `organizadores`
--

INSERT INTO `organizadores` (`organizador_id`, `usuario_id`, `biografia`, `verificado`, `verificado_por_admin`) VALUES
(8, 45, 'eventos', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `pago_id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `monto` decimal(10,2) DEFAULT NULL,
  `metodo` varchar(50) DEFAULT NULL,
  `estado` varchar(50) DEFAULT 'pendiente',
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `pedido_id` int(11) NOT NULL,
  `asistente_id` int(11) NOT NULL,
  `evento_id` int(11) NOT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `fecha_compra` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` varchar(50) DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`pedido_id`, `asistente_id`, `evento_id`, `total`, `fecha_compra`, `estado`) VALUES
(6, 42, 9, 300.00, '2025-11-11 09:08:40', 'completado'),
(7, 42, 9, 150.00, '2025-11-11 09:09:51', 'completado'),
(8, 42, 9, 50.00, '2025-11-11 09:16:03', 'completado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_items`
--

CREATE TABLE `pedido_items` (
  `pedido_item_id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio_unit` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido_items`
--

INSERT INTO `pedido_items` (`pedido_item_id`, `pedido_id`, `categoria_id`, `cantidad`, `precio_unit`) VALUES
(6, 6, 60, 3, 100.00),
(7, 7, 61, 3, 50.00),
(8, 8, 61, 1, 50.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registro_validacion`
--

CREATE TABLE `registro_validacion` (
  `validacion_id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `resultado` varchar(50) DEFAULT NULL,
  `dispositivo` varchar(100) DEFAULT NULL,
  `fecha_hora` timestamp NOT NULL DEFAULT current_timestamp(),
  `sincronizado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `solicitudes_eliminar_organizador`
--

CREATE TABLE `solicitudes_eliminar_organizador` (
  `id` int(11) NOT NULL,
  `organizador_id` int(11) NOT NULL,
  `motivo` text NOT NULL,
  `estado` enum('pendiente','aprobada','rechazada') DEFAULT 'pendiente',
  `fecha_solicitud` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tickets`
--

CREATE TABLE `tickets` (
  `ticket_id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `asistente_id` int(11) NOT NULL,
  `codigo_qr` varchar(255) DEFAULT NULL,
  `estado` varchar(50) DEFAULT 'activo',
  `fecha_emision` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_uso` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tickets`
--

INSERT INTO `tickets` (`ticket_id`, `pedido_id`, `categoria_id`, `asistente_id`, `codigo_qr`, `estado`, `fecha_emision`, `fecha_uso`) VALUES
(4, 6, 60, 42, 'PUM-2C30C65685', 'activo', '2025-11-11 09:08:40', NULL),
(5, 6, 60, 42, 'PUM-591506F29D', 'activo', '2025-11-11 09:08:40', NULL),
(6, 6, 60, 42, 'PUM-DE65C5083D', 'activo', '2025-11-11 09:08:40', NULL),
(7, 7, 61, 42, 'PUM-EC540074A2', 'activo', '2025-11-11 09:09:51', NULL),
(8, 7, 61, 42, 'PUM-8F69250261', 'activo', '2025-11-11 09:09:51', NULL),
(9, 7, 61, 42, 'PUM-115299B8C0', 'activo', '2025-11-11 09:09:51', NULL),
(10, 8, 61, 42, 'PUM-EFD43C2080', 'activo', '2025-11-11 09:16:03', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `usuario_id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `rol` enum('asistente','organizador','administrador') DEFAULT 'asistente',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `email_verificado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`usuario_id`, `nombre`, `apellido`, `email`, `telefono`, `password`, `rol`, `fecha_registro`, `email_verificado`) VALUES
(36, 'PumAdmin', 'Principal', 'pumfest2025@gmail.com', '+57 000 000 0000', '90377ffa76777f2b2fdbc6b798c43a56bb9e34d98bc64752d10b3563a6686af2', 'administrador', '2025-11-06 21:14:27', 1),
(42, 'Kevin Julián', 'Guerrero Penagos', 'kevinjgp0@gmail.com', '+57 322 227 8027', '$2y$10$FxuUt88LipoK5CSoMM/i8.IKbtlGP4q5LkIEP.7RJv4LffPpF1Gyq', 'asistente', '2025-11-08 05:04:41', 1),
(45, 'Kevin Julián', 'Guerrero Penagos', 'ASNARCKWARS@gmail.com', '+57 322 227 8027', '$2y$10$nLCKFAgiq4P//2wFGfV2PeMsCDBEnr.2sPZnqb8OH8SaLrQVp74n6', 'organizador', '2025-11-09 04:42:31', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `asistentes`
--
ALTER TABLE `asistentes`
  ADD PRIMARY KEY (`asistente_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `carritos`
--
ALTER TABLE `carritos`
  ADD PRIMARY KEY (`carrito_id`),
  ADD KEY `asistente_id` (`asistente_id`);

--
-- Indices de la tabla `carrito_items`
--
ALTER TABLE `carrito_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `carrito_id` (`carrito_id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `categorias_entrada`
--
ALTER TABLE `categorias_entrada`
  ADD PRIMARY KEY (`categoria_id`),
  ADD KEY `evento_id` (`evento_id`);

--
-- Indices de la tabla `codigos_verificacion`
--
ALTER TABLE `codigos_verificacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `entradas`
--
ALTER TABLE `entradas`
  ADD PRIMARY KEY (`entrada_id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `eventos`
--
ALTER TABLE `eventos`
  ADD PRIMARY KEY (`evento_id`),
  ADD KEY `organizador_id` (`organizador_id`);

--
-- Indices de la tabla `organizadores`
--
ALTER TABLE `organizadores`
  ADD PRIMARY KEY (`organizador_id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `verificado_por_admin` (`verificado_por_admin`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`pago_id`),
  ADD KEY `pedido_id` (`pedido_id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`pedido_id`),
  ADD KEY `asistente_id` (`asistente_id`),
  ADD KEY `evento_id` (`evento_id`);

--
-- Indices de la tabla `pedido_items`
--
ALTER TABLE `pedido_items`
  ADD PRIMARY KEY (`pedido_item_id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `registro_validacion`
--
ALTER TABLE `registro_validacion`
  ADD PRIMARY KEY (`validacion_id`),
  ADD KEY `ticket_id` (`ticket_id`);

--
-- Indices de la tabla `solicitudes_eliminar_organizador`
--
ALTER TABLE `solicitudes_eliminar_organizador`
  ADD PRIMARY KEY (`id`),
  ADD KEY `organizador_id` (`organizador_id`);

--
-- Indices de la tabla `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`ticket_id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `categoria_id` (`categoria_id`),
  ADD KEY `asistente_id` (`asistente_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuario_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administradores`
--
ALTER TABLE `administradores`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `asistentes`
--
ALTER TABLE `asistentes`
  MODIFY `asistente_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT de la tabla `carritos`
--
ALTER TABLE `carritos`
  MODIFY `carrito_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `carrito_items`
--
ALTER TABLE `carrito_items`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categorias_entrada`
--
ALTER TABLE `categorias_entrada`
  MODIFY `categoria_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT de la tabla `codigos_verificacion`
--
ALTER TABLE `codigos_verificacion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT de la tabla `entradas`
--
ALTER TABLE `entradas`
  MODIFY `entrada_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `eventos`
--
ALTER TABLE `eventos`
  MODIFY `evento_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `organizadores`
--
ALTER TABLE `organizadores`
  MODIFY `organizador_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `pago_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `pedido_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `pedido_items`
--
ALTER TABLE `pedido_items`
  MODIFY `pedido_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `registro_validacion`
--
ALTER TABLE `registro_validacion`
  MODIFY `validacion_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `solicitudes_eliminar_organizador`
--
ALTER TABLE `solicitudes_eliminar_organizador`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de la tabla `tickets`
--
ALTER TABLE `tickets`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `usuario_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD CONSTRAINT `administradores_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `asistentes`
--
ALTER TABLE `asistentes`
  ADD CONSTRAINT `asistentes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_asistente_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `carritos`
--
ALTER TABLE `carritos`
  ADD CONSTRAINT `carritos_ibfk_1` FOREIGN KEY (`asistente_id`) REFERENCES `asistentes` (`asistente_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `carrito_items`
--
ALTER TABLE `carrito_items`
  ADD CONSTRAINT `carrito_items_ibfk_1` FOREIGN KEY (`carrito_id`) REFERENCES `carritos` (`carrito_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `carrito_items_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categorias_entrada` (`categoria_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
